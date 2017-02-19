module.exports = function() {

    this.config = {
        debugMode: true
    };


    this.$get = function() {
        return this.config;
    }
};