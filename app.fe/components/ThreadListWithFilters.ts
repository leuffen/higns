// language=html
import {customElement, KaCustomElement, template} from "@kasimirjs/embed";
import {ThreadMessageList} from "./ThreadMessageList";
import {ThreadList} from "./ThreadList";

let html = `
    <div class="h-100 w-100 position-relative">
        <div class="row position-absolute top-0 w-100" style="height:40px; z-index: 4">
            <div class="row">
                
                <div class="col-6">
                    <input type="search" ka.bind="$scope.search" class="form-control" placeholder="Search" aria-label="Search">
                </div>
            </div>
        </div>
       <div class="position-absolute w-100 overflow-scroll" style="top: 60px; bottom: 30px" ka.content="messages">
           
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
        public selectedThreadId : string = null
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


    }
}
