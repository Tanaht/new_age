module.exports = function() {

    this.config = {
        debugMode: true,
        debugRouter: false,
        debugRest: true,
        debugPersistedQueue: true,
        base_uri: "/new_age/web",
        persistentStates: {
            UN_PERSISTED: 0,
            PERSISTED: 1,
            ON_PERSIST: 99,
            ERROR_PERSIST: -1,
        },
        user: {}
    };

    this.config.rest_uri = this.config.base_uri + "/app_dev.php/api";


    this.$get = function() {
        return this.config;
    }
};