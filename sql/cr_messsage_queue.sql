create table message_queue (
    id             int(11) NOT NULL AUTO_INCREMENT,
    od_instance_id int(11) DEFAULT NULL,
    od_session_id  int(11) DEFAULT NULL,
    status         char,
    request        text,
    response       text,
    create_date    datetime DEFAULT NULL,
    update_date    datetime DEFAULT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 ;

create table message_queue_pending (
    id             int(11)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;

create table message_queue_players (
    player_id     int(11),
    pid           int(11),
    heart_beat    datetime default null
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ;
