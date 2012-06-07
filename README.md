MaxIncident
===========

A way to create notifications based on the maximum number of incidents on a problem ticket in Zendesk

Setup:

You'll want to place this script on a server somewhere and then create a URL target in Zendesk.

The URL target needs to be as follows:

Title: The titel of your target (this can be anything).

URL: the location of your target but do not include any placeholders or other junk. We just want the location.

Method: GET

Attribute Name: id

No basic authentication unless you want it but you'd have to build that into the script.

Now create a trigger with the following settings:

Meet **all** of the following conditions:

Ticket is ... Updated


Meet **any** of the following conditions:

Type ... is ... Incident

Type ... changed to ... Incident


Perform these actions:

Notify target ... -target name from above-

Message:

{{ticket.id}}


How it works?

The system does the following:

1. A ticket is turned into an incident or is updated (and it already is an incident).

2. The URL target fires passing us the ticketid number.

3. Our script pulls the incident ticket and finds the problem ticket associated. 

4. We count the incidents attached to that problem ticket.

5. We tag the problem ticket with the tag linked_ and the number of linked incidents. For example, if you had 10 linked incidents you'd see the tag linked_10.

6. If there is already a tag of linked_ we remove it and add the new tag.


So what? How do I get notified?

You create a second notification that says if a ticket is updated and the tags contains linked_10 then send me a notification that says: Oh no! We have a ticket with 10 linked incidents on it!!! EEEEK! All this script really does is give you a way to modify tickets with a certain number of linked incidents. What you do after that is up to you. 

Can I use this for anything else?

Sure you can use this for reporting. How? Not a clue but it's a tag, use your imagination. 