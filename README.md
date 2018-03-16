# ![hubiC](Resources/Public/Images/hubic-logo.png) shared links for TYPO3

[![Build Status](https://travis-ci.org/filoucrackeur/hubic.svg?branch=master)](https://travis-ci.org/filoucrackeur/hubic) 
[![Code Coverage](https://scrutinizer-ci.com/g/filoucrackeur/hubic/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/filoucrackeur/hubic/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/filoucrackeur/hubic/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/filoucrackeur/hubic/?branch=master) 
[![Build Status](https://scrutinizer-ci.com/g/filoucrackeur/hubic/badges/build.png?b=master)](https://scrutinizer-ci.com/g/filoucrackeur/hubic/build-status/master)
[![Total Downloads](https://poser.pugx.org/filoucrackeur/hubic/downloads)](https://packagist.org/packages/filoucrackeur/hubic) 
> This extension lets you connect as many hubiC account as you want with TYPO3. You can manage the accounts in a TYPO3 backend module.

> ![Slack](http://betanews.com/wp-content/uploads/2015/03/slack_logo-50x50.jpg) Join the discussion on Slack in channel [**#ext-hubic**](https://typo3.slack.com/messages/ext-hubic)! – You don't have access to TYPO3 Slack? Get your Slack invitation [by clicking here](https://forger.typo3.org/slack)!

## Configure your hubiC account

First of all go to your [hubic account](https://hubic.com/home/browser/developers/) in developer section.
Add an app with your domain where your TYPO3 is installed. You must give a name and your domain name.

## Installation & Configuration

1. Install with composer (not required)
    ```bash
    composer require filoucrackeur/hubic
    ```
    or
    ```bash
    composer require typo3-ter/hubic
    ```
2. Go in TYPO3 extension Manager and activate "hubic" extension if is not already done
3. Go in the new menu "hubiC" in TYPO3 backend

## How it works
1. First go in your hubiC account [hubiC](https://hubic.com/home/)
2. Go in a page or sysfolder to add an "hubiC > Account"

![](Documentation/Images/NewRecordHubic.png)

3. Fill required fields

![](Documentation/Images/CreateNewAccount.png)

4. Go in the Admin tools > hubiC module menu

![](Documentation/Images/BackendMenuHubiC.png)

5. Click on the account you want to configure.
 
![](Documentation/Images/HubiCBackendModuleList.png)

6. Click on the button to get hubiC authorization WebApp

![](Documentation/Images/HubiCBackendModuleShowNotAuthenticated.png)

7. Fill your email and password and click on "Accept"

![](Documentation/Images/HubiCAuthentication.png)

8. If you success getting token 

![](Documentation/Images/TokenAdded.png)

9. Click in the account to see details

![](Documentation/Images/HubiCBackendModuleShow.png)

10. Add plugin in a page to list shared links

![](Documentation/Images/HubiCPluginConfiguration.png)

11. Go in frontend to view the result

![](Documentation/Images/FrontendPluginPreview.png)

## References

---------------

* [hubiC API](http://api.hubic.com)
* [hubiC docs extension TYPO3](https://typo3.org/extensions/repository/view/hubic)


