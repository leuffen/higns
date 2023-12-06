<?php

namespace App\BL\DataAccess\Type;

class T_DM_Thread_Message_AiDetails
{




    /**
     * What language is the mail written in? Use the ISO 639-1 code (e.g. "de" for German, "en" for English).
     * @var string
     */
    public string $isoCountryCode = "de";

    /**
     * The original text without previous messages. Preserve forwarded messages.
     *
     * @var string
     */
    public string $originalTextWithoutSignature = "";

    /**
     * Provide a short title for the originalTextWithoutSignature. Imagine you must find the mail later on by this title. 60 characters max.
     * Write in isoCountryCode language.
     *
     * @var string
     */
    public string $shortTitle;

    /**
     * Rewrite the content of originalTextWithoutSignature in the form of a short informal WhatsApp message to/from a friend.
     *
     * - Omit format Greeting and sign-offs
     * - Use a  casual, conversational tone, as if you're talking to a friend but very short.
     * - Write in very short, direct sentences or even bullet points to mimic the ease of a messaging app.
     * - Avoid complex sentence structures or jargon, unless it's relevant in the context.
     * - Emojis can be used to express emotions or emphasis but should be used sparingly.
     * - Ensure that the main point of your message remains clear and understandable.
     * - Emphasize *important keywords* by using *asterisks*.
     *
     * Write in the language specified in isoCountryCode.
     *
     * @var string
     */
    public string $shortDescription;

    /**
     * Is the email urgent? Provide true if the sender requested an urgent response.
     *
     * @var bool
     */
    public bool $urgent = false;

    /**
     * Is a response requested? Provide true if the sender requested a response or a response is expected.
     *
     * @var bool
     */
    public bool $responseRequested = false;


    /**
     * Return true if the mail contains data to fulfill a task (like address, instructions, etc).
     *
     * @var bool
     */
    public bool $containsData = false;

    /**
     * What questions have to be answered to generate a response to this mail?
     *
     * (Only for incoming mails, keep empty for outgoing mails)
     *
     * @var T_DM_Thread_Message_AiDetails_Question[]
     */
    public array $questions = [];

}
