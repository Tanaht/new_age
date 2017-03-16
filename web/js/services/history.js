/**
 * Created by Antoine on 16/03/2017.
 */
module.exports = function($log, rest, config) {
    /**
     * History Queue
     * @type {Array}
     */
    this.history = [];

    /**
     * Push history to queue
     */
    this.push = function(object) {
        this.history.push(object);
    };

    /**
     * poll history from queue
     */
    this.poll = function() {
        let object = this.history[0];
        return this.history.shift();
    };
};