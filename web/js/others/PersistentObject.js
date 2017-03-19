/**
 * Created by Antoine on 16/03/2017.
 * Object used to store some datas on bdd via rest service.
 */
function PersistentObject(route, options, formDatas, viewState) {
    this.route = route;
    this.options = options;
    this.formDatas = formDatas;

    /**
     * viewState looks like:
     * {
     *      title,
     *      subtitles,
     *      content-panel html (no more datas than a panel),
     *      state: unpersisted|persisted|error
     * }
     */
    this.viewState = viewState;


    /**
     * Triggered on Failure of resolve
     */
    this.onFailure = null;


    /**
     * Triggered on Success of resolve
     */
    this.onSuccess = null;

    /**
     * Update on Failure method
     */
    this.setOnFailure = function(onFailure) {
        this.onFailure = onFailure;
    };

    /**
     * Update on Success method
     */
    this.setOnSuccess = function(onSuccess) {
        this.onSuccess = onSuccess;
    };

    this.hydrateCsrfToken = function(rest) {
        let self = this;
        /*
            TODO: It's not secure to tell to generate token from client with clair token id in script. A fix would be to generate a token based on the resources [routename] we try to post.
         */
        rest.get('get_csrf_token', { intention: 'voeu_form_token_id'}, function(success) {
            self.formDatas.Token = success.data;
            self.persist(rest);
        }, function(error) {
            console.error('[PersistentObject] Unable to retrieve CsrfToken');
        });
    };
    /**
     * persist this PersistentObject to bdd via angular rest services
     * @return true|false
     */
    this.persist = function(rest) {
        let self = this;
        if(angular.isDefined(this.formDatas.Token)) {
            rest.post(this.route, this.options, this.formDatas, function(success) {
                delete self.formDatas.Token;
            }, function(error) {

            });
        }
        else {//hydrate token if not here
            this.hydrateCsrfToken(rest);
        }

        return false;
    };
}