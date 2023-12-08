import {customElement, KaCustomElement, template, timeAgo} from "@kasimirjs/embed";
import {api_call} from "@kasimirjs/app";
import {API} from "../_routes";

// language=html
let html = `
    <div class="list-group">
        <div class="row">
            <div class="col-4">ID</div>
            <div class="col-4">[[ threadMeta?.threadId ]]</div>
        </div>
        <div>
            Summary: [[ thread?.aiDetails.statusSummary ]]
        </div>
    </div>
`;




@customElement()
@template(html)
export class ThreadMetaDetails extends KaCustomElement {

    constructor(
        public subscription_id : string,
        public thread_id : string
    ) {
        super();
        let scope = this.init({
            threadList: null,
            threadId: thread_id,
            threadMeta: null,
            thread: null,
            subscription_id: subscription_id
        })
    }


    async connectedCallback(): Promise<void> {
        super.connectedCallback();


        let thread = await api_call(API.getthreadmessages_GET, {subscription_id: this.subscription_id, thread_id: this.thread_id})
        this.scope.thread = thread;

        let threadList = await api_call(API.getthreadlist_GET, {subscription_id: this.subscription_id})
        this.scope.threadMeta = threadList.threads.find(t => t.threadId === this.thread_id)
    }
}
