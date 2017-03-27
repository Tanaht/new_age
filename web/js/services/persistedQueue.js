/**
 * Created by Antoine on 16/03/2017.
 * This service is used to managed update to database
 */
module.exports = function($q, $log, rest, config) {

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

    this.size = function() {
        return this.persistedQueue.length;
    };


    /**
     * Return whether or not the PersistentObject in parameters is in queue.
     * @param object PersistentObject
     * @returns {boolean}
     */
    this.contains = function(object) {
        return !angular.equals(this.persistedQueue.indexOf(object), -1);
    };

    /**
     * Get head of the queue
     * @returns undefined|PersistentObject
     */
    this.first = function() {
        if(!this.hasNext())
            return undefined;

        for(let i = 0 ; i < this.persistedQueue.length ; i++) {
            if(!angular.equals(this.persistedQueue[i].state, config.persistentStates.ON_PERSIST))
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
        return this.size() > 0;
    };


    /**
     * Persist only one object
     * @param persistentObject
     * @returns {promise|undefined}
     */
    this.persistOne = function(persistentObject) {
        let deferred = $q.defer();
        let self = this;

        if(!this.contains(persistentObject)) {
            $log.error("[Service:persistedQueue] Trying to persist an invalid persistentObject");
            return deferred.promise;
        }

        if(persistentObject.state === config.persistentStates.PERSISTED) {
            $log.error("[Service:persistedQueue] Trying to persist an already persisted persistentObject");
            return deferred.promise;
        }


        persistentObject.persist(rest).then(function(success) {
            if(config.debugPersistedQueue && config.debugMode)
                $log.debug("[Service:persistedQueue] Success Persist");

            self.remove(persistentObject);
            deferred.resolve();
        }, function(error) {
            if(config.debugPersistedQueue && config.debugMode)
                $log.error("[Service:persistedQueue] Error Persist");
            deferred.reject();
        });

        return deferred.promise;
    };

    /**
     * Persist all PersistentObjects from persistedQueueQueue
     * @return {promise}
     */
    this.persist = function() {
        let deferred = $q.defer();
        let self = this;

        if(!this.hasNext()) {
            $log.error("[Service:persistedQueue] No persist to do");
            return deferred.promise;
        }

        let po = this.first();

        if(po.state === config.persistentStates.PERSISTED) {
            $log.error("[Service:persistedQueue] Trying to persist an already persisted persistentObject");
            return deferred.promise
        }



        po.persist(rest).then(function(success) {
            if(config.debugPersistedQueue && config.debugMode)
                $log.debug("[Service:persistedQueue] Success Persist");

            self.remove(po);

            if(self.hasNext()) {
                self.persist().then(function() {
                    deferred.resolve();
                },function() {
                    deferred.reject();
                })
            }
            else {
                deferred.resolve();
            }
        }, function(error) {
            if(config.debugPersistedQueue && config.debugMode)
                $log.error("[Service:persistedQueue] Error Persist");

            deferred.reject();
        });

        return deferred.promise;
    }
};