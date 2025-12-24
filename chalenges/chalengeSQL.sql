/*chalenge 1 */
1-
select u.nom , u.prenom ,count(s.id) from users u
Inner join coachs c on c.user_id = u.id
Inner join seances s on s.coach_id = c.user_id
group by u.nom , u.prenom 

2- 
select u.nom , u.prenom ,count(s.id) from users u
Inner join coachs c on c.user_id = u.id
Inner join seances s on s.coach_id = c.user_id
where s.statut = 'disponible'
group by u.nom , u.prenom 


3-
select DISTINCT(SELECT count(*) from seances where statut = 'reservee') /(select count(*) from seances) *100 as total from seances s;

4-
select u.nom , u.prenom ,count(s.id) as total from users u
Inner join coachs c on c.user_id = u.id
Inner join seances s on s.coach_id = c.user_id
group by u.nom , u.prenom  having  total >=3;

/*chalenge 2 */



