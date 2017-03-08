module.exports = function() {

    this.config = {
        debugMode: true
    };


    //TODO cannot be injected in controller or services
    this.$get = function() {
        return this.config;
    }
};