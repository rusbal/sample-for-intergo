Laravel.routes = {

    basepath: '/api/aml/',

    get(path, id) {
        let url = this.basepath + path

        if (id) {
            url += (url.endsWith('/') ? '' : '/') + id
        }

        return url
    },

    controller(path, id) {
        let parts = path.split('@')
        let url = '/' + parts[0]

        if (id) {
            url += (url.endsWith('/') ? '' : '/') + id
        }

        return url
    }
};

Laravel.route = (path, id = null) => {
    path = path.replace(/\./, '/')

    if (path.includes('@')) {
        return Laravel.routes.controller(path, id)
    } else {
        return Laravel.routes.get(path, id)
    }
};