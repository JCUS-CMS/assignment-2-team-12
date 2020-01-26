# CMS: Assignmnet 2
## Team 12

------------------------------------
### Group Members -

1. Aung Zay Ya - aungzayya1@my.jcu.edu.au
2. Sai Aung Sein Lin - saiaungseinlin@my.jcu.edu.au
3. Myo Min Htwe - myomin.htwe@my.jcu.edu.au
4. Ahnt Moe Hein - ahntmoeheinn@my.jcu.edu.au
------------------------------------

**Why VVV?**

*VVV allows us to edit our files as much as we want like easily renaming the files and son. Aside from that VVV base its Database cms.wordpress.local while other installations like Wp Distillery have it on ScotchBox. VVV gives us an easier access to migrate our database as wordpress is the one hosting it.*
<br> 
**Why WP_Sync_DB?**

*This plugin allows the user to migrate its whole database to a remote DB. It actually overwite the whole DB so, the user will have to careful in their transfer of DB. The advantage of this plugin is that it is easy to access and it is just one click awasy from uploading our local database to remote Db. Not only that we can also Export and pull the database. But in this project , we will just be using the push and pull function for our transfer of DB.**

<br/><br/>
## Local Environment Setup -
<br/><br/>
**Setting up VVV in your local machine**
<br/>
<br>
**To start the process, run the following commands:**

```
git clone https://github.com/Varying-Vagrant-Vagrants/VVV.git
cd VVV
vagrant plugin install vagrant-hostsupdater --local
vagrant up
```

<br/>

**ADDING NEW WP-SITE TO VVV**

<br/>

go to your VVV directory and look for _'config.yml'_ under VVV/config/. 
and add the following to your file

<br/>

###### new website for cms classroom repo

<br/>

```
  wordpress-cms:
    skip_provisioning: false
    description: "A standard WP install, useful for building plugins, testing things, etc"
    repo: https://github.com/Varying-Vagrant-Vagrants/custom-site-template.git
    custom:
      # locale: it_IT
      delete_default_plugins: true
      install_plugins:
        - query-monitor
    hosts:
      - cms.wordpress.local
 ```


**Setting up GITHUB in your new website/public_html**

<br/>

Assuming you are already in your VVV directory<br/>
```
cd www/wordpress-cms/public_html/
```

now delete the wp-content file.
```
rm -rf wp-content
```

then set up git in the directory
```
git init
```


Link your local git with the remote<br/>
```
git remote add origin https://github.com/JCUS-CMS/assignment-2-team-12
```

now rename assignment-2-team-12 file name to wp-content<br/>
```
(go to the directory and rename the file)
```
now cd to the wp-content folder <br/>
```
cd wp-content
```
now pull from the remote branch<br/>
```
git pull
```


  
**SETTING UP STAGING ON YOUR LOCAL ENVIRONMENT:**

<br/>

create a new branch and link it with your git remote header <branch>:<br/>
  
```
git checkout staging
```

now for easy pull and push upstream it to your origin/<branch>=staging.<br/>
  
```
git branch --set-upstream-to=origin/header staging
```

now pull the latest upadtesf branch commits:<br/>

```
git pull
```

<br/>

**CHANGING GIT BRANCH IN LOCAL ENVIRONMENT:**

<br/>

this is the command to shift between branches in your local environment:<br/>

```
git checkout <branch-name>
```

for example if you are in staging branch and want to shift to master branch:<br/>

```
git checkout master
```

<br/>

Example of the staging process

<br/>

For example lets say you have added some new source codes on your footer branch and now want to update it.
Then follow the following command:<br/>
<br/>
*Check first on which you are on. Make sure that you are on the correct branch*
<br>
Add the changed files to your git:

```
git add .
```

now commit the changes you have made:<br/>

```
git commit -m "<your-commit-message>"
```

now push the changes :<br/>

```
git push
```


<br/>
  
Check out the staging URL!<br/>

https://myominh.sgedu.site/stagetest/

<br/>

After going for many tests and runs, we were finally able to push it for Production .This is our Production Site!


https://myominh.sgedu.site/doctorsconnect/


------------------------------------
