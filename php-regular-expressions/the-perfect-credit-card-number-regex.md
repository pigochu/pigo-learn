完美的信用卡號正規表達式文章說明
============================
這篇文章保留了原文，原始出處來自 http://www.richardsramblings.com/2012/12/the-perfect-credit-card-number-regex/
為了避免該站不見了，故保留原文備用

----------

The Perfect Credit Card Number RegEx
====================================

I’d love to give you a single credit-card-number-matching regular expression and tell you that my über-cool rule is The One Rule to match them all. I’d be lying.

Many developers have difficulty defining regular expressions; even more unknowingly fail to understand the appropriate usage of regexes and how and when to apply them. My experience has shown that regex validation of CCNs is the most commonly misused of all because developers fail to differentiate the subtle underpinnings:

- Does it resemble a credit card number?
- Is it probably a credit card number?

Despite their apparent similarities, the two questions and their answers are not the same.

## Does it Resemble a Credit Card Number? ##

Resemblance is, by far, the most common type of validation performed on CCNs. Any decent shopping cart application or payment processing system will perform syntactic or basic regex validation at the time a customer enters their payment information; the goal is to assist the customer and help ensure that they have entered the correct number. Some use server-side validation, others use JavaScript client-side validation — the better ones use both.

During normal card number validation for commerce purposes, the widest possible net should be used — allowing anything that basically resembles a credit card number to pass input verification. Once basic resemblance is assured, rely on third-party merchant services to pass judgment on card validity and acceptance.

The most basic generally accepted rule for validating credit card numbers is that Visa cards start with a “4″, Mastercard starts with “51″ through “55″, and Discover usually starts with “6011″ or “65″ — all card account numbers of which being generally 16 digits long. American Express card numbers start with “34″ or “37″, and are 15 digits long. Almost all modern credit card numbers end with a Luhn-10 (or modulus-10) checksum digit.

Tossing usability aside, most single-field form-based input of credit card numbers disallow the use of delimiters, and expect the numbers to be specified in a single grouping (without spaces or dashes). Given those basic assumptions, most use a basic regular expression such as the following to validate the majority of CCNs:
```
\b(?:3[47]\d|(?:4\d|5[1-5]|65)\d{2}|6011)\d{12}\b
```

For non-commerce applications (or for the abysmally few commerce sites that embrace usability), 16-digit card numbers may contain spaces or dashes between blocks of four digits (e.g. NNNN-NNNN-NNNN-NNNN), while American Express card numbers are sometimes separated into blocks of four, six, and five digits (e.g. NNNN-NNNNNN-NNNNN). In order to cast an even wider net that also would match account numbers with dashes and spaces, the following regex could apply:
```
\b(?:3[47]\d{2}([\s-]?)\d{6}\1\d|(?:(?:4\d|5[1-5]|65)\d{2}|6011)([\s-]?)\d{4}\2\d{4}\2)\d{4}\b
```

> Author’s Note: For simple form validation it might be easier to simply strip out dashes and spaces after input (e.g. s/[\s-]//g), then apply the simpler of the two regexes above. There’s no reason verification can’t occur in two or more steps.

After simple regex matching, the next most-common step is verification of the trailing check digit, a mathematical algorithm designed to detect transpositional typing errors (e.g. “4123…” instead of “4213…”). However, for use in e-commerce, blanket reliance on Luhn checks is cautioned:

> “While the great majority of 16-digit Visa cards will pass modulus-10 checking, acquirers and merchants should be aware that some valid 19-digit debit cards may not pass modulus-10 checking. It is recommended that modulus-10 checking not be performed, particularly if both debit and credit are accepted.” — Visa Transaction Acceptance Device Guide 2.0 (March 2011)

Regex engines do not utilize mathematical functions or formulas during inspection, therefore Luhn checking is not possible with regular expressions.

## Is it Probably a Credit Card Number? ##

Unlike form input validation that matches anything that positively resembles a credit card number, DLP applications or systems that create alerts or notifications based on the discovery of CCNs must essentially negatively ignore everything that doesn’t strongly resemble a credit card number. There’s a huge difference between asking “Hey, this is a credit card number; do you agree that it looks like a credit card number?” and asking “Hey, I’ve found something; do you think it’s a credit card number?”

While ideal for lightly vetting payment accounts, using wide open net patterns that merely check for basic resemblance to credit card numbers generate too many false positives. Most vendors and developers haven’t realized this, as evidenced by regexes found in their documentation or published by employee moderators in their support forums.

- OpenDLP matches "4563 9601 2200-1999" with its Visa regex, but won’t match "4563960122001999", making their regex somewhat useless — but, hey, it’s open-source software, right? Fix it yourself.
~~~
    (\D|^)4[0-9]{3}(\ |\-|)[0-9]{4}(\ |\-|)[0-9]{4}(\ |\-|)[0-9]{4}(\D|$)
~~~
 
- WebSense similarly matches "4563\9601-2200\1999" with its Visa regex, but misses the legitimate "4563960122001999". To their credit, WebSense indicates that improperly written regexes “can create many false-positive incidents intercepted on the system, can slow down Websense Data Security, and impede analysis.” They fail to mention that “stupidly written regexes will not match anything useful.”
~~~
    \b(4\d{3}[\-\\]\d{4}[\-\\]\d{4}[\-\\]\d{4})\b
~~~
    
- McAfee HDLP catches a lot more than the other two, but overextends matches within random numerical data like "234563 9601-2200 1999-12" with its regex. Does it look like there’s a credit card number in there? McAfee says there is, wasting your time.
~~~
    (4\d{3})(-?|\040*)(\d{4}(-?|\040*?)){3}
~~~

All three of the above solutions are doing it wrong; they should be using a narrower net, decreasing volume of matches in favor of focused probability — allowing everything to pass through that doesn’t match a much stricter credit card validation process. Even regular-expressions.info, a widely respected reference on regular expressions, written by the creator of RegexBuddy (the defacto regex tool for Windows platforms), incorrectly advises that “unless your company uses 16-digit numbers for other purposes, you’ll have few false positives.” The author then apocryphally suggests that the following regex is “the only way” to find card numbers with spaces or dashes in them:
~~~
\b(?:\d[ -]*?){13,16}\b
~~~

I’m sure you realize how catastrophic that regex would be to use for discovery purposes across an entire file system, resulting in a myriad false positives such as "37 36 35 34 2011-12-31".

Of the remaining major DLP players not mentioned above, only [Symantec](http://www.symantec.com/data-loss-prevention) and [Code Green Networks](http://www.codegreennetworks.com/) appear to be doing it right, cross-referencing intelligent datastores with Luhn-validated results from sophisticated regexes that match optional separators and eliminate likely false positives by ignoring sequential or repeated digits. Code Green Networks, for example, uses the following regex pattern that matches most major 15- or 16-digit credit card numbers:
```
\b(3[47]\d{2}([ -]?)(?!(\d)\3{5}|123456|234567|345678)\d{6}\2(?!(\d)\4{4})\d{5}|((4\d|5[1-5]|65)\d{2}|6011)([ -]?)(?!(\d)\8{3}|1234|3456|5678)\d{4}\7(?!(\d)\9{3})\d{4}\7\d{4})\b
```
Regular expressions alone cannot establish viable probability, especially wide nets.

> Author’s Note: My integrity compels the disclosure that I personally created the credit card  number regular expression above for Code Green Networks.

## Recommendations ##

Form validation of credit cards requires the following steps:

1.  Check the credit cards against the widest-possible net regex; you don’t want to reject valid payment methods for a customer who is actively in the process of sending you money. Nothing’s stopping Danish electronic payment provider Nets Holdings (IIN: 457123) from issuing debit card number 4571 2345 6789 0111, a seemingly obviously fake account number that passes all regex, mod-10, and IIN-assignment tests.
2.  Perform Luhn-10 validation only when applicable, warning customers appropriately of potential mistypes, applying rejection only with 100% certainty. You must make the business decision yourself as to whether or not the less-typical 13-, 17-, 18-, or 19-digit credit, debit, or prepaid payment cards found mostly outside the United States should be supported.
3.  Validate the credit card number through common merchant services, such as Verified by Visa or MasterCard Developer Zone; or — if not for e-commerce — match the CCN to ones stored in your clean database (or, better yet, match a seeded MD5 of the CCN to a list of seeded hashes in your database to prevent unnecessary local storage of credit card data in the clear).

Applications using credit card filtering or discovery methods require a different approach:

1.  Match potential credit card data against the narrowest-possible net regex, eliminating up front 80-90% of false positives. It is probably better to miss one real credit card number than match and manually review 1,000 invalid account numbers, but that again is a business decision you must make for yourself.
2.  Always perform Luhn-10 validation, reducing false positives by 90%, while failing to match an acceptable estimated 9 ten-thousandths of one percent (0.0009%) due to a small number of cards issued without a Luhn-10 checksum digit.
3.  If possible, verify each potential match against a clean datastore of all credit card numbers in which you are interested. Based on the most common CCN format, up to one billion account numbers per IIN will pass regex and Luhn-10 validation — registering ten million clean credit card numbers to cross-check inspection results will reduce 99% of false positives.

> Author’s Note: I have yet to see a customer have a completely “clean” registration database. Every customer has fake or false data in their datastore that must be filtered out prior to registration and inspection — every field of fake data that you allow through registration will generate false positives.

If you’ve gotten this far, be sure to read my “sticky” page on credit card number regexes.