import {customElement, KaCustomElement, template, timeAgo} from "@kasimirjs/embed";
import {api_call} from "@kasimirjs/app";
import {API} from "../_routes";
import {OriginalMailModal} from "../modals/OriginalMailModal";

// language=html
let html = `
    <div class="list-group list-group-numbered">
        <div ka.for="let tm of thread?.messages" >
            <div class="row" ka.classList.justify-content-end="tm.type==='email_outgoing'">
                <div class="col-10 m-1">
                    <div class="card">
                    <div class="card-body">
                        <div ka.if="tm.showHeader">
                            <i ka.if="tm.type==='email_incoming'" class="bi bi-box-arrow-in-right fs-4 pe-1"></i>
                            <i ka.if="tm.type==='email_outgoing'" class="bi bi-box-arrow-left fs-4 pe-1""></i>

                            <span class="fw-bold">[[tm.from ]]</span><span class="ps-3 text-muted">[[tm.ago]]</span>

                        </div>
                        <div ka.if="tm.aiDetails === null">bitte warten...</div>
                        <div ka.if="tm.aiDetails !== null">[[ tm.aiDetails.shortDescription ]]<button class="btn btn-link" ka.on.click="$fn.details(tm.imapId)">Details</button></div>
                    </div>
                </div>
                
            </div>
        </div>
       
    </div>
`;


function filterThread(thread : any) {
    let last = null;
    for (let msg of thread.messages) {
        msg.ago = timeAgo(new Date(msg.dateTime));
        msg.showHeader = true;
        if (last !== null && last.from === msg.from) {
            msg.showHeader = false;
        }
        last = msg;
    }
    return thread;
}


@customElement()
@template(html)
export class ThreadMessageList extends KaCustomElement {

    constructor(
        public subscription_id : string,
        public thread_id : string
    ) {
        super();
        let scope = this.init({
            thread: null,
            subscription_id: subscription_id,
            $fn: {
                details: async (messageId: string) => {
                    let modal = new OriginalMailModal();
                    let message = scope.thread.messages.find(m => m.imapId === messageId);

                    await modal.show(message.originalText);

                }
            }
        })
    }

    async connectedCallback(): Promise<void> {
        super.connectedCallback();

        let thread = await api_call(API.getthreadmessages_GET, {subscription_id: this.subscription_id, thread_id: this.thread_id})
        thread = filterThread(thread);
        this.scope.thread = thread;
    }
}
