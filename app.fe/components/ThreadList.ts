import {customElement, KaCustomElement, template, timeAgo} from "@kasimirjs/embed";
import {api_call} from "@kasimirjs/app";
import {API} from "../_routes";

// language=html
let html = `
    <ol class="list-group">
        <div ka.for="let t of threadList?.threads">
            <li ka.if="t.show"  class="list-group-item list-group-item-action d-flex justify-content-between align-items-start" ka.classList.active="t.threadId===selectedThreadId">

                <div class="row w-100">
                    <div class="col-1" style="width: 20px">
                        <input type="checkbox" ka.bindarray="$scope.selected" ka.attr.value="t.threadId">
                    </div>
                    <div class="col-10">
                        <div class="ms-2 me-auto" >
                            <div class="fw-bold">[[ t.title ]] ([[t.threadId]])</div>
                            <div>
                                <i class="bi bi-box-arrow-in-right fs-4"></i> [[ t.agoIn ]]
                                <i class="bi bi-box-arrow-left fs-4"></i> [[ t.agoOut ]]
                                | <a ka.attr.href="'/static/' + subscription_id + '/thread/' + t.threadId ">See</a>
                            </div> 
                           
                        </div>   
                       
                    </div>
                    <div class="col-1">
                        <span class="badge bg-primary rounded-pill">14</span>
                    </div>
                </div>
                
                  
     
            </li>
       </div>
    </ol>
`;


function filterThreadList(threadList) {
    threadList.threads.forEach((t) => {
        t.agoIn = timeAgo(new Date(t.lastInboundDate))
        t.agoOut = timeAgo(new Date(t.lastOutboundDate))
        t.show = true
    });
    return threadList;
}


@customElement()
@template(html)
export class ThreadList extends KaCustomElement {

    constructor(
        public subscription_id : string,
        public selectedThreadId : string = null
    ) {
        super();
        let scope = this.init({
            threadList: null,
            selected: [],
            selectedThreadId: this.selectedThreadId,
            subscription_id: subscription_id
        })
    }

    async sortBy(sort : string, asc: boolean = true)
    {
        console.log("sort", sort, asc);
        this.scope.threadList.threads.sort((a, b) => {
            if (asc)
                return a[sort] > b[sort] ? 1 : -1
            else
                return a[sort] < b[sort] ? 1 : -1
        })
        this.scope.render();
    }

    async filter(filter : string)
    {
        console.log("filter", filter);
        this.scope.threadList.threads.forEach((t) => {
            if (t.title.toLowerCase().includes(filter.toLowerCase()))
                t.show = true
            else
                t.show = false
        });
        this.scope.render();
    }

    async connectedCallback(): Promise<void> {
        super.connectedCallback();

        let threadList = await api_call(API.getthreadlist_GET, {subscription_id: this.subscription_id})
        threadList = filterThreadList(threadList);
        this.scope.threadList = threadList;
        await this.sortBy("lastOutboundDate", false);
    }
}
