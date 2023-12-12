import {customElement, KaCustomElement, template, timeAgo} from "@kasimirjs/embed";
import {api_call} from "@kasimirjs/app";
import {API} from "../_routes";

// language=html
let html = `
    <div class="list-group">
        <div class="row">
            <div class="col-2">ID</div>
            <div class="col-4">[[ threadMeta?.threadId ]]</div>
            <div class="col-6 text-end">
                <!-- Button group starts here -->
                <div class="btn-group btn-group me-3 " role="group" aria-label="Button Group">
                    <!-- Show/Hide Button -->
                    <a type="button" ka.on.click="$fn.updateMeta('isUnread', !threadMeta.isUnread)" class="btn btn-outline-secondary">
                        <i class="bi" ka.classlist.bi-eye="threadMeta.isUnread" ka.classlist.bi-eye-slash=" ! threadMeta.isUnread"></i>
                    </a>
                    <!-- Goto Thread Button -->
                    <button type="button" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-up-right-square"></i>
                    </button>
                    <!-- Details Button -->
                    <button type="button" class="btn btn-outline-secondary">
                        <i class="bi bi-info-square"></i>
                    </button>
                </div>
                <!-- Button group ends here -->
                
            </div>
        </div>
        <div>
            Summary: [[ thread?.aiDetails?.statusSummary ]]
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
            subscription_id: subscription_id,

            $fn: {
                updateMeta: async (key : string, value : any) => {
                    await api_call(API.setthreadmetafield_POST, {subscription_id: this.subscription_id, thread_id: this.thread_id}, {[key]: value})
                }
            }
        })
    }


    async connectedCallback(): Promise<void> {


        let thread = await api_call(API.getthreadmessages_GET, {subscription_id: this.subscription_id, thread_id: this.thread_id})
        this.scope.thread = thread;


        let threadList = await api_call(API.getthreadlist_GET, {subscription_id: this.subscription_id})
        this.scope.threadMeta = threadList.threads.find(t => t.threadId === this.thread_id)

        super.connectedCallback();

    }
}
