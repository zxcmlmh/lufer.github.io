::call hexo g > deploy.txt
::call hexo d >> deploy.txt
call git add -A . > gitadd.txt
call git commit -m "backup" > gitcommit.txt
call git push origin bakup > gitpush.txt