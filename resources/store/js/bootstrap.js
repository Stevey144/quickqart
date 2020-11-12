import axios from "axios";
import toast from "./services/toast";

require('./services/Bee');
require('./JwtHelper');
window.slugify = require('slugify');
window.countryList = "United States|Afghanistan|Aland Islands|Albania|Algeria|American Samoa|Andorra|Angola|Anguilla|Antarctica|Antigua and Barbuda|Argentina|Armenia|Aruba|Australia|Austria|Azerbaijan|Bahamas|Bahrain|Bangladesh|Barbados|Belarus|Belgium|Belize|Benin|Bermuda|Bhutan|Bolivarian Republic of Venezuela|Bonaire, Sint Eustatios and Saba|Bosnia and Herzegovina|Botswana|Bouvet Island|Brazil|British Indian Ocean Territory|Brunei Darussalam|Bulgaria|Burkina Faso|Burundi|Cambodia|Cameroon|Canada|Cape Verde|Cayman Islands|Central African Republic|Chad|Chile|China|Christmas Island|Cocos (Keeling) Islands|Colombia|Comoros|Congo|Cook Islands|Costa Rica|Cote D'Ivoire|Croatia|Cuba|Curacao|Cyprus|Czech Republic|Democratic People's Republic of Korea|The Democratic Republic of the Congo|Denmark|Djibouti|Dominica|Dominican Republic|Ecuador|Egypt|El Salvador|Equatorial Guinea|Eritrea|Estonia|Ethiopia|Falkland Islands (Malvinas)|Faroe Islands|Federated States of Micronesia|Fiji|Finland|The Former Yugoslav Republic of Macedonia|France|French Guiana|French Polynesia|French Southern Territories|Gabon|Gambia|Georgia|Germany|Ghana|Gibraltar|Greece|Greenland|Grenada|Guadeloupe|Guam|Guatemala|Guernsey|Guinea|Guinea-Bissau|Guyana|Haiti|Heard Island and McDonald|Islands|Holy See (Vatican City State)|Honduras|Hong Kong|Hungary|Iceland|India|Indonesia|Iraq|Ireland|Islamic Republic of Iran|Isle of Man|Israel|Italy|Jamaica|Japan|Jersey|Jordan|Kazakhstan|Kenya|Kiribati|Kuwait|Kyrgyzstan|Laos People's Democratic Republic|Latvia|Lebanon|Lesotho|Liberia|Libya|Liechtenstein|Lithuania|Luxembourg|Macao|Madagascar|Malawi|Malaysia|Maldives|Mali|Malta|Marshall Islands|Martinique|Mauritania|Mauritius|Mayotte|Mexico|Monaco|Mongolia|Montenegro|Montserrat|Morocco|Mozambique|Myanmar|Namibia|Nauru|Nepal|Netherlands|New Caledonia|New Zealand|Nicaragua|Niger|Nigeria|Niue|Norfolk Island|Northern Mariana Islands|Norway|Oman|Pakistan|Palau|Palestinian Territory, Occupied|Panama|Papua New Guinea|Paraguay|Peru|Philippines|Pitcairn|Plurinational State of|Bolivia|Poland|Portugal|Puerto Rico|Qatar|Republic of Korea|Republic of Moldova|Reunion|Romania|Russian Federation|Rwanda|Saint Barthelemy|Saint Helena, Ascension and Tristan da Cunha|Saint Kitts and Nevis|Saint Lucia|Saint Martin (French)|Saint Pierre and Miquelon|Saint Vincent and the Grenadines|Samoa|San Marino|Sao Tome and Principe|Saudi Arabia|Senegal|Serbia|Seychelles|S. Georgia & S.|Sandwich Isls.|Sierra Leone|Singapore|Sint Maarten (Dutch)|Slovakia|Slovenia|Solomon Islands|Somalia|South Africa|South Sudan|Spain|Sri Lanka|Sudan|Suriname|Svalbard and Jan Mayen|Swaziland|Sweden|Switzerland|Syrian Arab Republic|Taiwan, Province of China|Tajikistan|Thailand|Timor-Leste|Togo|Tokelau|Tonga|Trinidad and Tobago|Tunisia|Turkey|Turkmenistan|Turks and Caicos Islands|Tuvalu|Uganda|Ukraine|United Arab Emirates|United Kingdom|United Republic of Tanzania|Uruguay|USA Minor Outlying Islands|Uzbekistan|Vanuatu|Viet Nam|Virgin Islands (British)|Virgin Islands (USA)|Wallis and Futuna|Western Sahara|Yemen|Zambia|Zimbabwe";

window.baseUrl = '/api/';
window.parseError = function (error) {
    console.log(error);
    const err = {
        error: null,
        title: 'Error'
    };

    if (error.status) {
        if (typeof error == 'string') {
            err.error = error;
        } else {
            switch (error.status) {
                case 422:
                    err.title = 'Validation Error';
                    if (error.hasOwnProperty('data')) {
                        let errors = false;
                        if (error.data.hasOwnProperty('error') && error.data.error.hasOwnProperty('errors')) {
                            errors = error.data.error.errors;
                        }
                        if (error.data.hasOwnProperty('errors')) {
                            errors = error.data.errors;
                        }

                        if (errors) {
                            let et = '';
                            Object.values(errors).forEach(e => {
                                e.forEach(ex => {
                                    et += `<div>${ex}</div>`;
                                });
                            });
                            err.error = et;
                        }
                    }
                    break;
                case 404:
                case 403:
                case 400:
                    err.title = '';
                    if (error.hasOwnProperty('data')) {
                        if (error.data.hasOwnProperty('suspension')) {
                            err.title = 'Account suspended';
                            err.error = error.data.suspension;
                        } else if (error.data.hasOwnProperty('message')) {
                            err.error = error.data.message;
                        }
                    }

                    break;
                case 500:
                    err.title = 'Error';
                    if (error.hasOwnProperty('data') && error.data.hasOwnProperty('message')) {
                        err.error = error.data.message;
                    }
                    break;
                case 401:
                    err.title = 'Access Denied';
                    err.error = 'Your session have expired, Login again to continue.</a>';
                    localStorage.removeItem(appSetting.jwt);
                    let route = location.href;
                    setTimeout(() => location.href = window.rootUrl + '/login?next=' + route, 1000);
                    break;
            }
        }
    } else {

    }

    if (!err.error) {
        err.error = error.statusText;
    }
    return err;
};

window.errorResponseHandler = function (error) {
    if (!error.config.hasOwnProperty('errorHandle') || error.config.errorHandle) {
        if (error.response) {
            const err = window.parseError(error.response);
            localStorage.setItem('lastError', err.error);
            toast.error(err.error, err.title);
        }
    }

    return Promise.reject(error);
};

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */


window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let tk = '', tkf = document.head.querySelector('meta[name="csrf-token"]');

if (tkf) {
    tk = tkf.content;
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = tk;
}
window.axios.interceptors.request.use((config) => {
    const method = config.method.toLowerCase(), methods = ['delete', 'put', 'patch'],
        index = methods.indexOf(method);
    if (index > -1) {
        let data = {};
        try {
            if (config.data && config.data.toString() !== undefined) {
                data = config.data;
            }
        } catch (e) {
        }

        data['_method'] = methods[index].toUpperCase();
        data['_token'] = tk;
        config.data = data;

        config.method = 'post';
    }
    return config;
});
window.axios.interceptors.response.use(
    response => response,
    errorResponseHandler
);

window.appSetting = {
    name: 'AddyTH',
    jwt: 'app_tkn',
    id_tkn: 'id_tkn',
    stores_tkn: 'stores_tkn',
    pref_store: 'pref_store',
    pref_currency: 'pref_currency',
    recapV3Key: '6LfAcuAUAAAAAFfQ3c3M274ZeWmbVHISJ2BwvLW6',
    roles: {
        SYSADMIN: 'sysadmin',
        STUDENT: 'student'
    },
    baseUrl: 'quickqart.com'
};

window.eventName = {
    socialLogin: 'social-login',
    loggedIn: 'logged-in',
    scrollToTop: 'scroll-to-top',
    sessionActive: 'session-active',
    pageHeader: '-page-header',
    triggerUpload: 'trigger-upload',
    uploadDone: 'done-uploading',
    uploadProgress: 'uploading',
    uploadError: 'error-uploading',
    modals: {
        addCourseContent: 'add-course', addAsset: 'add-asset',
        quizQuestion: 'add-quiz-question', createNotification: 'create-notification-modal',
        createCategories: 'create-categories', createCampus: 'create-campuses',createDepartment: 'create-departments',
        userContentUpload: 'user-content-upload', categoryModal: 'category-modal',
        assignTeacher: 'assign-teacher', createBatchModal: 'create-edit-category-batch'
    }
};

window.permissionList = {
    manage_users: 'manage users',
    manage_roles: 'manage roles',
    manage_students: 'manage students',
    manage_settings: 'manage settings',
    manage_time_schedule: 'manage time schedule',
    manage_courses: 'manage courses',
    manage_classroom: 'manage classroom',
    manage_teachers_courses: 'manage teachers courses',
    manage_categories: 'manage categories',
};

window.roles = {
    STUDENT: 'student',
    SYSADMIN: 'sysadmin',
    ADMIN: 'admin',
    TEACHER: 'teacher',
};

window.can = function(permission) {
    const token = getUserIdToken('permission');
    if (!token) {
        return false;
    }

    const permissions = permission.split('|');

    for (let perm in permissions) {
        if (token.permission.includes(permissions[perm])) {
            return true;
        }
    }

    return false;
};

function getUserIdToken(type) {
    let token = null;
    try {
        token = JSON.parse(localStorage.getItem('id_tkn'));
    } catch (e) {
    }
    if (!token || !token[type] || !token[type].length) {
        return false;
    }

    return token;
}

window.hasUserType = function(type) {
    const token = getUserIdToken('role');
    if (!token) {
        return false;
    }

    const roles = type.split('|');

    for (let role in roles) {
        if (token.role.includes(roles[role])) {
            return true;
        }
    }

    return false;
};

/**
 * Next we will register the CSRF Token as a common header with Axios so that
 * all outgoing HTTP requests automatically have it attached. This is just
 * a simple convenience so we don't have to attach every token manually.
 */

let token = localStorage.getItem(appSetting.jwt);
axios.defaults.headers.common['Content-Type'] = 'application/json';
axios.defaults.headers.common['Accept'] = 'application/json';
if (token) {
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
}
axios.defaults.headers.common['PREF-STORE'] = localStorage.getItem(appSetting.pref_store);
