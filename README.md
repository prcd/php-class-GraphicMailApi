# php-class-GraphicMailApi

If you are looking for an email solution I would recommend looking elsewhere.
If you are forced to work with GraphicMail then I feel your pain.
I wrote this class to reduce the number of times I want to throw my laptop out of the window - it seems to be working...

See the [wiki](https://github.com/prcd/php-class-GraphicMailApi/wiki) for a user guide.

### Key features
* Chat with GraphicMail’s API in a much more civilised way
* Variables and key-words are now logically named
* Responses are checked and returned in a consistent manner
* Set and retrieve data using `PascalCase`, `camelCase` or `snake_case`

### Issues that are eliminated using the class
* For some calls, the API will respond with a string if there are no records or an XML file if there are records.
* The structure of the XML file changes depending on the number of results.
* Sometimes errors are returned when a perfectly valid request has been made.
* etc...
