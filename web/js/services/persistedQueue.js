/**
 * Created by Antoine on 16/03/2017.
 * This service is used to managed update to database
 */
module.exports = function($log, rest, config) {

    /**
     * History Queue
     * @type {Array}
     */
    this.persistedQueue = [];

    /**
     * Push persistedQueue to queue
     */
    this.push = function(object) {
        object.state = config.persistentStates.UN_PERSISTED;
        this.persistedQueue.push(object);
    };


    /**
     * Return whether or not the PersistentObject in parameters is in queue.
     * @param object PersistentObject
     * @returns {boolean}
     */
    this.contains = function(object) {
        return this.persistedQueue.indexOf(object) != -1;
    };

    /**
     * Get head of the queue
     * @returns undefined|PersistentObject
     */
    this.first = function() {
        if(!this.hasNext())
            return undefined;

        for(let i = 0 ; i < this.persistedQueue.length ; i++) {
            if(this.persistedQueue[i].state != config.persistentStates.ON_PERSIST)
                return this.persistedQueue[0];
        }
        return this.persistedQueue[0];
    };

    /**
     * remove PersistentObject from the queue
     * @param object PersistentObject
     */
    this.remove = function(object) {
        if(this.contains(object)) {
            this.persistedQueue.splice(this.persistedQueue.indexOf(object), 1);
        }
    };


    this.hasNext = function() {
        return this.persistedQueue.length != 0;
    };


    /**
     * Persist all PersistentObjects from persistedQueueQueue
     * @param onPersistedSuccess: promise callable called when all queue is persisted.
     * @param onPersistedFailure: promise callable called when an error occured.
     */
    this.persist = function(onPersistedSuccess, onPersistedFailure) {
        let self = this;

        if(!this.hasNext()) {
            if(angular.isDefined(onPersistedSuccess))
                onPersistedSuccess();
            return;
        }

        let po = this.first();

        if(po.state === config.persistentStates.PERSISTED) {
            self.remove(po);
            self.persist(onPersistedSuccess, onPersistedFailure);
            return;
        }

        po.persist(rest, function(success) {
            if(config.debugPersistedQueue && config.debugMode)
                $log.debug("[Service:persistedQueue] Success Persist");

            self.remove(po);
            self.persist(onPersistedSuccess, onPersistedFailure);
        }, function(error) {
            if(config.debugPersistedQueue && config.debugMode)
                $log.error("[Service:persistedQueue] Error Persist");

            if(angular.isDefined(onPersistedFailure))
                onPersistedFailure();
        });
    }
};