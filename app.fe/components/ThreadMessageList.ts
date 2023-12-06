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
                        <div><span class="fw-bold">[[tm.from ]]</span> [[tm.ago]] | <button ka.on.click="$fn.details(tm.imapId)">D</button></div>
                        <div ka.if="tm.aiDetails === null">bitte warten...</div>
                        <div ka.if="tm.aiDetails !== null">[[ tm.aiDetails.shortDescription ]]</div>

                    </div>
                </div>
                
            </div>
        </div>
       
    </div>
`;


function filterThread(thread : any) {
    for (let msg of thread.messages)
        msg.ago = timeAgo(new Date(msg.dateTime));
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
