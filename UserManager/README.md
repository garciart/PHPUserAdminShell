#Information

##Role Levels in User Manager

The role level attribute contains an integer from 0 to 20, which you can use to assign capabilities to users and/or control access to pages in your own application. Using Wordpress roles as an example, you may assign Subscribers to level 1, Administrators to level 16, etc., or you can prevent anyone with a role level less than 10 from accessing a certain page or activating a plugin.

In the PHP User Manager, these values provide users with the following capabilities:

- 0: Anonymous and unauthorized user. Cannot access User Manager pages.
- 1 - 5: Authenticated user, aka Member. Only authorized to view and edit his or her own profile.
- 6 - 10: Authenticated user, aka Sponsor. Authorized to view and edit his or her own profile and view (but not edit) the content of User Manager pages, such as User and Role Administration.
- 11 - 15: Authenticated user, aka Editor. Authorized to view, add, edit, and delete profiles and view (but not edit) the content of other User Manager pages, such as Role Administration.
- 16 - 20: Authenticated user, aka Administrator. Authorized to edit all profiles and roles.

