module.exports = function() {

    this.config = {
        debugMode: true,
        debugRouter: false,
        debugHistory: true,
        base_uri: "/new_age/web",
    };

    this.config.rest_uri = this.config.base_uri + "/app_dev.php/api";


    this.$get = function() {
        return this.config;
    }
};