Laravel.routes = {

    /**
     * Define routes here
     */
    routes: {
        listing: 'listing'
    },

    basepath: '/api/aml/',

    get(path) {
        return this.basepath + path;
    }
};

Laravel.route = (path) => {
    return Laravel.routes.get(path);
};