/**
 * Created by Antoine on 16/03/2017.
 */
function HElement(url, datas, viewState) {
    const UNPERSISTED = "UNPERSISTED";
    const PERSISTED = "PERSISTED";
    const ERROR = "ERROR";
    this.url = url;
    this.datas = datas;
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
    this.viewState.state = UNPERSISTED;


    /**
     * Triggered on Failure of resolve
     */
    this.onFailure = function() {
        console.debug("Failure at " + this.url + " | " + this.datas);
    };


    /**
     * Triggered on Success of resolve
     */
    this.onSuccess = function() {
        console.debug("Success at " + this.url + " | " + this.datas);
    };

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

    /**
     * resolve this HistoryElement
     * @return true|false
     */
    this.resolve = function(rest) {
        return false;
    };
}