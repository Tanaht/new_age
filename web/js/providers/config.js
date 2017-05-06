module.exports = function() {

    this.config = {
        initializationCompleted: false,
        debugMode: true,
        debugRouter: false,
        debugRest: false,
        debugPersistedQueue: false,
        base_uri: "/new_age/web",
        persistentStates: {
            UN_PERSISTED: 0,
            PERSISTED: 1,
            ON_PERSIST: 99,
            ERROR_PERSIST: -1,
        },
        user: {},
        users: []
    };

    this.config.rest_uri = this.config.base_uri + "/app_dev.php/api";


    this.$get = function() {
        return this.config;
    }
};