import {customElement, KaCustomElement, template, timeAgo} from "@kasimirjs/embed";
import {api_call, api_url} from "@kasimirjs/app";
import {API} from "../_routes";
import {threadService} from "../worker/thread-service";

// language=html
let html = `
    <div class="">
        <table class="table table-hover">
            <tr ka.for="let m of media">
                <td>[[ m.filename ]]</td>
                <td>[[ m.date ]]</td>
                <td>
                    <a class="btn btn-sm" ka.attr.href="$fn.getDownloadLink(m.id)" target="_blank"><i class="bi bi-eye"></i></a>
                    <a class="btn btn-sm" ka.attr.href="$fn.getDownloadLink(m.id, true)"><i class="bi bi-download"></i></a>
                </td>
            </tr>
        </table>
        
    </div>
    
`;




@customElement()
@template(html)
export class ThreadAttachmentList extends KaCustomElement {

    constructor(
        public subscription_id : string,
        public thread_id : string
    ) {
        super();
        let scope = this.init({
            threadList: null,
            threadId: thread_id,
            media: null,
            subscription_id: subscription_id,

            $fn: {
                getDownloadLink: (id : string, download :boolean = false) => {
                    return api_url(API.downloadmedia_GET, {media_id: id, download: download}).url;
                }
            }
        })
    }


    async connectedCallback(): Promise<void> {


        let thread = await threadService.getThreadById(this.thread_id);
        console.log("thread", thread);
        this.scope.media = thread.media ?? [];



        super.connectedCallback();

    }
}
