
import {api_call, router} from "@kasimirjs/app";
import {API} from "../_routes";
import {isset} from "@kasimirjs/embed";


class ThreadService {

    #lastId = null;
    #lastRet = null;

    async getThreadById(threadId: string): Promise<any> {
        if ( threadId === null || threadId === undefined)
            throw "error";

        if (this.#lastId === threadId) {
            return this.#lastRet;
        }
        this.#lastRet = api_call(API.getthreadmessages_GET, {subscription_id: router.currentRoute.route_params.subscription_id, thread_id: threadId})
        this.#lastId = threadId;

        return this.#lastRet;
    }

}
export const threadService = new ThreadService();
