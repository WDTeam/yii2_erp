#  关于业务实体状态机的设计范式

假定通过业务分析，我们有很多个业务实体， 以某一个业务实体Entity开始分析。

1. 首先根据这个业务实体的段内容分析，可以拆解为几个表。
   Entity （基本信息）
   Entity  （经常更新）
   Entity  （更新更频繁，但是是以业务流为分割，例如订单，可以把状态更新和支付更新分割）
   各个字表，一般都通过实体逐渐ID来引用。
   例如，以订单为例，
   order_info  order_trans_record  order_status order_worker_info 等等。

2. 在所有分割后的，凡是涉及到状态的，应该有一个实体状态表 entity_status， 例如order_status.
   而涉及到状态的更新，应该操作entity_status表。


entity_status

id

Entity_id （通过这个键来找到这个状态是对应的那个实体的状态）

entity_status_current_state (通过这个子段，来获知实体的当前状态)

entity_status_current_state_txt (通过这个子段，来获知实体的当前状态的具体内容，比如已发送，已服务等)

entity_status_code(通过这个状态，对每个历史记录表entity_status_history的发生的每个状态，求和sum，
  保证历史记录表中不会丢失，或者更改。用这个字段来保证历史记录完整性)

3. 实体历史记录表 entity_status_history， 例如 order_status_history

id  
entity_status_cur_state （当前状态）  
entity_status_cur_state_txt （当前状态的具体内容）
entity_status_nxt_state(下一个应该进入的状态，通过这个来判断状态机代码逻辑的正确性)  
entity_status_nxt_state_txt (下一个状态的具体内容)
entity_memo (状态备注)
createa_at  （创建时间）
updated_at  （更新时间）

4. 实体日志记录表 entity_log
这个表几乎是记录了这个实体所有的信息，而且，一定不能用引用，一定要用具有阅读语义的内容来填充这个表的内容。
理论上讲，这个表，大部分都是插入， 保留包括实体状态在内的所有记录信息，每条快照得记录，所有的一个动作变化
都要往这个记录里保存。


entity_name
entity_money
entity_cur_state
entity_next_state

...

以上内容不一定准确，供参考。
