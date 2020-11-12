class MyUploadAdapter {

    constructor( loader ) {
        this.loader = loader;
        this.cancel = false;
    }
    upload() {
        return this.loader.file
            .then( file => new Promise( ( resolve, reject ) => {
                const CancelToken = window.axios.CancelToken;

                let formData = new FormData();
                formData.append('file', file);

                window.axios.post(baseUrl + 'upload', formData, {
                    cancelToken: new CancelToken((c) => {
                        this.cancel = c;
                    }),
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }).then(response => {
                    if (response) {
                        const uploadRecord = response.data.data;
                        resolve({
                            default: uploadRecord.url
                        });
                        return;
                    }

                    reject('Unable to complete');
                }).catch((error) => {
                    reject('Unable to complete upload');
                });
            } ) );
    }

    abort() {
        if ( this.cancel ) {
            this.cancel();
        }
    }

}

export function CustomUploadAdapterPlugin( editor ) {
    editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
        return new MyUploadAdapter( loader );
    };
}
