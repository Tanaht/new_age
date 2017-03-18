module.exports = function() {

    this.config = {
        debugMode: true,
        debugRouter: true,
        base_uri: "/new_age/web",
    };

    this.config.rest_uri = this.config.base_uri + "/app_dev.php/api";


    //TODO cannot be injected in controller or services
    this.$get = function() {
        return this.config;
    }
};