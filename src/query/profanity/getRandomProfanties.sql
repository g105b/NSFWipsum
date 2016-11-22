select
	upper(word) as content

from
	tibz

order by rand()
limit 1