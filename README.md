## Instructions:

- clone this repository
- generate the `.env` file with the DB connection details
- run `php artisan key:generate`
- migrate the DB structure with `php artisan migrate:refresh`

## Notes

I made a lot of assumptions on how the requests would be formated and to save time I did not implement validation on the requests, I also didn't make any normalization on the url (removing GET parameters from the urls).

## API endpoints:
#### /public/api/visits (POST): route to store visits
POST fields:
- link (encoded url of the page, e.g.: "%2Fclothing-sports-baby%2Fsynergistic-bronze-keyboard")
- link_type (['homepage', 'static-page', 'product', 'category', 'checkout'])
- customer_id (expecting integer)
- timestamp (MySQL datetime format, e.g. "2021-03-26 12:52:47")

#### /public/api/links/hits/ (GET): get count of visits on a specific link
Parameters:
- link (encoded url of the page)
- start_date (MySQL datetime format, e.g. "2021-03-26 12:52:47")
- end_date (MySQL datetime format, e.g. "2021-03-26 12:52:47")
The dates are optional, without any of them the result will contain all time visits or the query will be limited by only one of them

Example response:
`{
"id": 9,
"url": "/jewelry-games-clothing/awesome-rubber-plate",
"type": "product",
"visits_count": 9
}`


#### /public/api/link_types/hits/{type} (GET): get count of visits on a specific link type
The `{type}` must be one of the possible page types (['homepage', 'static-page', 'product', 'category', 'checkout']).
This endpoint accepts start_date and end_date the same as previous endpoint

Example response:
`{
"type": "category",
"visits_count": 102
}`



#### /public/api/journey/{customer_id} (GET): get specific customer "journey" and other customers with identical journeys
`{customer_id}` must be the same format as in the visists POST endpoint
This endpoint will return an array of links visited with their timestamps and an array of customer ids with identical journey.

Example response:
`{
"customer_id": "11",
"journey": [
  {
"link_id": 1,
"timestamp": "2021-03-30 11:14:04"
},
  {
"link_id": 2,
"timestamp": "2021-03-31 09:37:43"
},
  {
"link_id": 3,
"timestamp": "2021-04-04 18:45:32"
},
  {
"link_id": 4,
"timestamp": "2021-04-04 22:28:24"
},
  {
"link_id": 5,
"timestamp": "2021-04-05 04:28:11"
}
],
"identical_journeys": [
  12,
  13
],
}`
