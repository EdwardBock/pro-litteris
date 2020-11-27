import { Button, TextareaControl, TextControl, BaseControl } from "@wordpress/components";
import {useIsSavingPost, useIsPostDirtyState, useProLitteris} from '../hooks/use-pro-litteris.js'
import { dateFormat } from "../utils.js";


const Pixel = ({pixel = {}})=>{
    const {url} = pixel;
    return <TextControl 
        label="Pixel"
        value={url}
        readOnly
    />
}

const Message = ({message = {}, draft = {}, onSubmitReport})=>{

    const isDirtyState = useIsPostDirtyState();
    const isSaving = useIsSavingPost();

    if(
        typeof draft.pixelUid === typeof undefined 
        && typeof message.pixelUid === typeof undefined
    ) {
        return null;
    }

    const isReported = typeof message.reported !== typeof undefined;

    const {
        pixelUid,
        title,
        plaintext,
        participants,
    } = message.pixelUid ? message : draft;

    return <>
        <h3>Meldung</h3>
        <TextControl 
            label="UID"
            value={pixelUid}
            readOnly
        />
        <TextControl 
            label="Titel"
            value={title}
            readOnly
        />
        <TextareaControl 
            label={`Text (${plaintext.length} Zeichen)`}
            value={plaintext}
            readOnly
        />
        <BaseControl 
            label="Autoren"
        >
            <ul style={{
                listStylePosition: 'inside',
                margin: 0,
                marginBottom: 20,
            }}>
                {participants.map(p=>
                    <li 
                        style={{
                            background:'#efefef',
                            borderRadius: 4,
                            padding: "6px 10px",
                            border: "1px solid #757575"
                        }}
                        key={p.memberId}
                    >
                        <Participant {...p}/>
                    </li>
                )}
            </ul>
        </BaseControl>
        
        {isReported ? 
            <>
                <p className="description">{dateFormat(parseInt(message.reported)*1000)}.</p>
                <p>Meldung war erfolgreich 🎉</p>
            </>
            :
            <Button
                disabled={isDirtyState || isSaving || isReported}
                isPrimary
                title="Bitte speichern vor dem Melden."
                onClick={onSubmitReport}
            >
                Jetzt melden
            </Button>
        }
    </>
}

const Participant = ({memberId, firstName, surName, internalIdentification})=>{
    return <a href={`/wp-admin/user-edit.php?user_id=${internalIdentification}`}>
        {firstName} {surName} ({memberId})
    </a>
}

const Plugin = ()=>{
    const [state, submitMessage] = useProLitteris();

    if(typeof state.info === typeof "") {
        return <p>{state.info}</p>
    }

    if(typeof state.error === typeof ""){
        return <p>Error: {state.error}</p>
    }
    const pixel = state.pixel;
    if(!pixel){
        return <p>No valid pixel found.</p>
    }

    return <>
        <Pixel pixel={pixel} />
        <hr />
        <Message 
            message={state.message} 
            draft={state.messageDraft} 
            onSubmitReport={()=>{
                submitMessage();
            }}
        />
    </>
}

export default Plugin;