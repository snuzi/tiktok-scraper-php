
This is a PHP implementation for scraping Tiktok (or Musically) through API.


## Scraper API
- `getUser($uid)` Returns user profile data. `$uid` is user's Tiktok unique id.
- `getUserVideos($uid)` Returns a list of user videos. `$uid` is user's Tiktok unique id.
- `searchUser($keyword)` Returns user search results.
- `getVideo($uid)` Returns a video datails. `$uid` is video Tiktok unique id.

**Your contribution is welcome!**

## Use this scraper
In order to use and make requests to Tiktok API, some extra device parameters are needed. These parameters should be extracted from your mobile phone by using a man in the middle proxy. I used PacketCapture for Android, you may use any proxy application you like. Grab the following parameters: `device_id`, `iid`, `openudid`.

## Run the example
- Install commposer packages in the root of the project.
`composer install`
- Run `php demo.php` to see the results