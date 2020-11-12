<template>
    <div class="wrapper">
        <form @submit.prevent="addCategory()" class="form">
            <div class="m-header except">
                <div class="d-flex justify-content-between">
                    <h5 class="header-text">{{isEdit ? 'Edit' : 'Add a'}} category</h5>
                    <span @click.prevent="close()" style="cursor: pointer">
                    <i class="material-icons" style="font-size: 25px">close</i></span>
                </div>
            </div>
            <div class="m-body" style="min-height: 80px!important">
                <div class="form-group">
                    <input class="form-control" v-model="form.name" required placeholder="Enter category name">
                </div>
            </div>
            <div class="m-footer border-0" style="border-top: 0!important">
                <div class="d-block text-center">
                    <button type="submit" style="min-width: 130px" class="btn btn-primary mb-4 mx-1 py-2 px-3">
                        <span>{{isEdit ? 'Update' : 'Add'}} Category</span>
                    </button>
                    <button type="button" @click.prevent="close()" class="btn btn-light mb-4 mx-1 py-2">
                        Close
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import Spinner from "../Spinner";

    export default {
        name: "AddEditProductCategory",
        components: {Spinner},
        props: ['category'],
        data() {
            return {
                form: {
                    name: '',
                    properties: []
                },
                isEdit: false,
            }
        },
        computed: {},
        methods: {
            addCategory() {
                this.close(this.form);
            },
            addProperty(propertyNames = ['']) {
                propertyNames.forEach(prop => {
                    this.form.properties.push({
                        name: prop,
                        values: ''
                    });
                });
            },
            close(data = false) {
                this.$emit('category-modal-close', data);
            }
        },
        mounted() {
            if (this.category) {
                this.isEdit = true;
                this.form = {...this.form, ...this.category};
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/variables';

    .wrapper {
        display: flex;
        justify-content: center;
        max-width: 450px;
        box-shadow: 0 10px 25px 0 rgba(50, 50, 93, 0.07), 0 5px 15px 0 rgba(0, 0, 0, 0.07);
        border-radius: 5px;

        .form {
            width: 450px;
            background: $app-background;
        }

        .form-label {
            display: block;
        }

        .header-text {
            font-size: 18px;
        }
    }

    .btn-light {
        background: #bfbebe;
    }

    .btn {
        &:not(.except) {
            min-width: 100px;
        }
    }
</style>
