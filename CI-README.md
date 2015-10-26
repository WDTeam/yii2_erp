#/usr/bin/rsync -avH  --delete --exclude '.git' /var/lib/jenkins/workspace/dev-ejj-enterprise-boss/  rsyncuser@10.44.160.164::dev-ejj-enterprise-boss   --password-file=/etc/rsync.pass

ssh 10.44.160.164 'cd /code/ejj-enterprise-boss && git pull '
#ssh 10.44.160.164 'git pull'

# 因为目录问题，引起很多小问题，需要注意，别删除这句话
#

ssh 10.44.160.164 '/usr/local/php/bin/php /code/ejj-enterprise-boss/init  --env=Development  --overwrite=a'

ssh 10.44.160.164 'chmod -R 777 /code/ejj-enterprise-boss/boss/web/assets'
ssh 10.44.160.164 'chmod -R 777 /code/ejj-enterprise-boss/boss/runtime'

ssh 10.44.160.164 'cd /code/ejj-enterprise-boss && /usr/local/php/bin/php /code/ejj-enterprise-boss/yii  migrate/down all  --interactive=0'
ssh 10.44.160.164 'cd /code/ejj-enterprise-boss && /usr/local/php/bin/php /code/ejj-enterprise-boss/yii  migrate/up all  --interactive=0'
ssh 10.44.160.164  '/code/ejj-enterprise-boss/boss/web/upload -R '
