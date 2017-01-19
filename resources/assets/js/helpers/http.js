Laravel.http = {
    get(path) {
        return new Promise(function(resolve, reject){
            axios.get(
                Laravel.route(path),
                Laravel.commonParam()

            ).then(function (response) {
                resolve(response.data);

            }).catch(function (error) {
                console.log(error);
                reject(error);
            });
        });
    }
};
