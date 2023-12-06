import {customElement, KaCustomElement, template} from "@kasimirjs/embed";
import {api_call} from "@kasimirjs/app";
import {API} from "../_routes";

// language=html
let html = `
    <ol class="list-group list-group-numbered">
        <li ka.for="let t of threadList?.threads" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
            
                
            <div class="ms-2 me-auto">
                <div class="fw-bold">[[ t.title ]] ([[t.threadId]])</div>
                <a ka.attr.href="'/static/' + subscription_id + '/thread/' + t.threadId ">See</a>
            </div>
            <span class="badge bg-primary rounded-pill">14</span>
        </li>
       
    </ol>
`;


@customElement()
@template(html)
export class ThreadList extends KaCustomElement {

    constructor(
        public subscription_id : string
    ) {
        super();
        let scope = this.init({
            threadList: null,
            subscription_id: subscription_id
        })
    }

    async connectedCallback(): Promise<void> {
        super.connectedCallback();

        this.scope.threadList = await api_call(API.getthreadlist_GET, {subscription_id: this.subscription_id})


    }
}
