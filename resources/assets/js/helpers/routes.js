Laravel.routes = {

    basepath: '/api/aml/',

    get(path, id) {
        let url = this.basepath + path

        if (id) {
            url += (url.endsWith('/') ? '' : '/') + id
        }

        return url
    }
};

Laravel.route = (path, id = null) => {
    path = path.replace(/\./, '/')
    return Laravel.routes.get(path, id)
};