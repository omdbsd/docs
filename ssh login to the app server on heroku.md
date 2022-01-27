
https://linuxtut.com/en/726900e015e2f62d0ba6/

1 Go to the app directory && heroku login

$ cd app_name
$ heroku login --interactive
2 ssh Specify the application you want to connect to

$ heroku git:remote --app app_name
3 Connect to server with ssh

$ heroku run bash
What to do if you can't run "Run console" on Heroku
1 If you can confirm the running one-off processes as shown below after executing the command, You need to stop the process.

$ heroku ps
Free dyno hours quota remaining this month: 978h 53m (97%)
Free dyno usage for this app: 20h 4m (2%)
For more information on dyno sleeping and how to upgrade, see:
https://devcenter.heroku.com/articles/dyno-sleeping

=== run: one-off processes (1)
run.2767 (Free): up 2018/11/23 21:06:53 +0900 (~ 9m ago): bash

=== web (Free): node server.js (1)
web.1: up 2018/11/23 21:12:24 +0900 (~ 4m ago)
2 Use the following command to specify the process ID and terminate the process.

$ heroku ps:stop run.2767(Process ID)


refer:
-----------

https://stackoverflow.com/questions/45385384/how-can-i-run-as-root-on-heroku

You cannot run as root on a Heroku Dyno, as Dynos are effectively containers, isolated from the host system. However, you should be able to install most packages via Buildpacks, either via first party supported buildpacks, thirdparty buildpacks, or via heroku-buildpack-apt. Attempting to alter any system files, will likely either not work, or will have unintended consequences.
