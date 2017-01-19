Laravel.params = {

    /**
     * Define params here
     */
    common: {
        user: Laravel.userId
    },

    basepath: '/api/aml/',

    get(path) {
        if (path === null) {
            return { params: this.common }
        }
    }
};

Laravel.param = (path = null) => {
    return Laravel.params.get(path);
};

Laravel.commonParam = () => {
    return { params: Laravel.params.common };
};