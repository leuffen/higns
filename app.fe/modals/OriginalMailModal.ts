import {FlexModal} from "@kasimirjs/kit-bootstrap";

// language=html
const html = `
    
    <div>
        <pre style="width: 100%; height: 100%; resize: none;" readonly ka.textContent="mail"></pre>
        
    </div>

`

export class OriginalMailModal extends FlexModal {

    public constructor() {
        super("Original Mail", html, ["Close"], {mail: ""});
    }


    public async show(mail: string): Promise<any> {
        this.scope.mail = mail;
        return super.show(this.scope);
    }


}
