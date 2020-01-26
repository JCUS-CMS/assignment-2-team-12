# CMS: Assignmnet 2
## Team 12

------------------------------------
### Group Members -

1. Aung Zay Ya - aungzayya1@my.jcu.edu.au
2. Sai Aung Sein Lin - saiaungseinlin@my.jcu.edu.au
3. Myo Min Htwe - myomin.htwe@my.jcu.edu.au
4. Ahnt Moe Hein - 

------------------------------------
## Local Environment Setup -
<br/><br/>
**Setting up VVV in your local machine**
<br/>
*run the following commands:*

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
 
<br/>

If you are setting this for first time then it should be added on the line 47 after the two.wordpress.test<br/>
and then run this command from VVV/ directory:<br/>
```
vagrant reload --provision
```

<br/>

**Setting up GITHUB in your new website/public_html**

<br/>

Assuming you are already in your VVV directory<br/>
```
cd www/wordpress-cms/
```

now we make a copy of your public_html/wp-config.php<br/>
```
cp public_html/wp-config.php wp-config.php
```

then we delete the public_html and create a new directory with same name<br/>
```
rm -rf public_html && mkdir public_html
```

then cd inside public_html<br/>
```
cd public_html
```

Setup git in your directory<br/>
```
git init
```

Create a new branch for easier push and pull<br/>
```
git checkout -b development
```

Link your local git with the remote<br/>
```
git remote add origin https://github.com/JCUS-CMS/assignment-2-team-12
```



now pull from the remote branch<br/>
```
git pull
```

now copy the wp-config.php file back to your public_html<br/>
```
cp ../wp-config.php wp-config.php
```


  
**SETTING UP STAGING ON YOUR LOCAL ENVIRONMENT:**

<br/>

create a new branch and link it with your git remote staging <branch>:<br/>
  
```
git checkout -b staging
```

now for easy pull and push upstream it to your origin/<branch>=staging.<br/>
  
```
git branch --set-upstream-to=origin/staging staging
```

now pull the latest staging branch commits:<br/>

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

**EXAMPLE - FOR STAGING**

<br/>

_For example lets say you have added some new feature to your development branch and now want to update it to staging branch
Then follow the following command:<br/>
You have to run the following command from your development branch -_<br/>
<br/>
Add the changed files to your git:

```
git add .
```

now commit the changes you have made:<br/>

```
git commit -m "<your-commit-message>"
```

now push the changes from development to staging :<br/>

```
git push origin staging
```

_**origin** = your remote git repo_
<br/>
_**staging** = your <branch> that you want to push to_

<br/>
  
now check out the staging URL for changes:<br/>

https://myominh.sgedu.site/stagetest/

------------------------------------

<br/>

This is our Production Site.


https://myominh.sgedu.site/doctorsconnect/
