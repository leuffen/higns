// language=html
import {customElement, ka_session_storage, ka_sleep, KaCustomElement, template} from "@kasimirjs/embed";
import {ThreadMessageList} from "./ThreadMessageList";
import {ThreadList} from "./ThreadList";

let html = `
    <div class="h-100 w-100 position-relative">
        <div class="row position-absolute top-0 w-100" style="height:40px; z-index: 4">
            <div class="row pt-2">
                
                <div class="col-6">
                    <input type="search" ka.bind="$scope.search" class="form-control" placeholder="Search" aria-label="Search">
                </div>
                <div class="col-3">
                    <select ka.options="{all: 'Alle', visible: 'Sichtbare'}" ka.bind.default="'all'" ka.bind="$scope.showFilter" class="form-select" aria-label="Default select example">
                       
                    </select>
                </div>
                    
            </div>
        </div>
       <div ka.ref="'scroller'" class="position-absolute w-100 overflow-scroll" style="top: 60px; bottom: 30px" ka.content="messages">
           
       </div>
        <div class="row position-absolute top-0 w-100" style="height:30px">
            
        </div>
    </div>
`;


@customElement()
@template(html)
export class ThreadListWithFilters extends KaCustomElement {

    constructor(
        public subscription_id : string,
        public selectedThreadId : string = null,
        public showFilter : string = "all"
    ) {
        super();
        let scope = this.init({
            messages: null,
            search: "",
            subscription_id: subscription_id,
            $on: {
                change: async () => {
                    console.log("change");
                    this.scope.messages.filter(this.scope.search)
                }
            }
        })
    }

    async connectedCallback(): Promise<void> {
        super.connectedCallback();

        this.scope.messages = new ThreadList(this.subscription_id, this.selectedThreadId)

        let sessionStore = ka_session_storage({scroll: 0}, "threadListWithFilters");

        await ka_sleep(100);
        this.scope.$ref.scroller.scrollTop = sessionStore.scroll;

        console.log("init", sessionStore.scroll);
        this.scope.$ref.scroller.addEventListener("scroll", () => {
            sessionStore.scroll = this.scope.$ref.scroller.scrollTop;
        });

    }
}
