mae_event_categories
====================

Forked from https://github.com/marebe1602/mae_event_categories

purpose
-------
if you have lots of events and using different calendars is not enough to organize your events, this plugin allows the definition of additional categories for filtering events in eventlist frontend modules.
It also comes with an event filter module that alows visitors to chose sthe category to be displayed in the list.



description
-----------
mae_event_categories adds a global operation to the calendar module which allows to define a non-hierarchical list of categories.
One or more of these categories can be assigned to an event.
The eventlist module is extended by the possibility to specify, which categories should be shown in the list.
Access to category management can be configured by a single permission

the getAllEvents hook is used to filter events for the eventlist, if categories have been configured in the specific frontend module.
Eventlists without configured categories will work as if the plugin wasn't there.
If you delete a category, all references in tl_module and tl_calendar_events will be removed, too. There will be no warning, if the category is in use.
You may filter events by category to see, if it is still assigned anywhere, before deleting (this may be improved in a later version).

The category filter module allows visitors to filter the displayed events by categories.



customized places within contao
-------------------------------
- Account Manager / [Users, User groups]:
  new legend "event categories" on top of "Calendar permissions"
  allow edit categories and specify default categories for new events

- Content / Events:
  new global operation "Categories"
  new field "Categories" in event header (tl_calendar_events)

- Themes / Frontend modules:
  new field "Event categories" in frontend modules eventlist, calendar and eventmenu (tl_module)

- new Event Category filter module

More info at:
http://www.martin-eberhardt.com/mae_event_categories.html
