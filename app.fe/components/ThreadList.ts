import {customElement, KaCustomElement, template, timeAgo, timeTo} from "@kasimirjs/embed";
import {api_call} from "@kasimirjs/app";
import {API} from "../_routes";

// language=html
let html = `<ol class="list-group">
    <div ka.for="let t of threadList?.threads">
        
        <li ka.if="t.show"  class="list-group-item list-group-item-action d-flex justify-content-between align-items-start" ka.classList.active="t.threadId===selectedThreadId">
            <div class="row w-100">
                <div class="col-1" style="width: 20px">
                    <input type="checkbox" ka.bindarray="$scope.selected" ka.attr.value="t.threadId">
                </div>
                <div ka.on.click="window.location.href='/static/' + subscription_id + '/thread/' + t.threadId " class="col-10" style="cursor:pointer"  >
                    <div class="ms-2 me-auto">
                        <div class="text-nowrap" ka.classlist.fw-bold="t.isUnread" ka.classlist.text-decoration-line-through="t.isArchived">[[ t.title ]]</div>
                        <div class="row">
                            <div class="col-12">
                                <i class="bi bi-box-arrow-in-right fs-4"></i> [[ t.agoIn ]]
                                <i class="ms-3  bi bi-box-arrow-left fs-4"></i> [[ t.agoOut ]]
                                <span ka.if="t.resubmissionDate !== null">
                                    <i class="ms-3 bi fs-4" ka.classlist.bi-clock="new Date(t.resubmissionDate) > new Date()" ka.classlist.bi-alarm-fill="new Date(t.resubmissionDate) <= new Date()"></i> [[ t.resubmissionIn ]]
                                </span>
                            </div>
                           
                        </div>
                    </div>
                </div>
                <div class="col-1">
                    <span class="badge bg-primary rounded-pill">14</span>
                </div>
            </div>
        </li>
    </div>
</ol>`;


function filterThreadList(threadList) {
    threadList.threads.forEach((t) => {
        t.agoIn = timeAgo(new Date(t.lastInboundDate))
        t.agoOut = timeAgo(new Date(t.lastOutboundDate))
        t.resubmissionIn = timeTo(new Date(t.resubmissionDate))
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

    async sortBy(sort : string|null, asc: boolean = true)
    {

        this.scope.threadList.threads.sort((a, b) => {
            if (sort === null) {
                if (a.resubmissionDate !== null && b.resubmissionDate === null)
                    return -1
                if (a.resubmissionDate === null && b.resubmissionDate !== null)
                    return 1
                if (a.resubmissionDate !== null && b.resubmissionDate !== null)
                    return a.resubmissionDate > b.resubmissionDate ? 1 : -1
                if (a.isUnread && ! b.isUnread)
                    return -1
                if ( ! a.isUnread && b.isUnread)
                    return 1
                if (a.isArchived && ! b.isArchived)
                    return 1
                if ( ! a.isArchived && b.isArchived)
                    return -1

                return 0;
            }
            if (asc)
                return a[sort] > b[sort] ? 1 : -1
            else
                return a[sort] < b[sort] ? 1 : -1
        })
        this.scope.render();
    }

    async filter(filter : string, showFilter: string = "all")
    {
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
        await this.sortBy(null, false);
    }
}
