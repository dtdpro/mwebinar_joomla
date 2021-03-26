CREATE TABLE `#__mwebinar_pages`
(
    `id`         int(11) NOT NULL,
    `webinar_id` int(11) DEFAULT NULL,
    `type`       varchar(20) COLLATE utf8_unicode_ci  NOT NULL,
    `content`    longtext COLLATE utf8_unicode_ci     NOT NULL,
    `ordering`   int(11) NOT NULL,
    `published`  int(11) NOT NULL DEFAULT '1',
    `title`      varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `#__mwebinar_webinaranswer`
(
    `id`         int(11) NOT NULL,
    `answer`     varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `webinar`    int(11) NOT NULL,
    `page`       int(11) NOT NULL,
    `sessionid`  varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `created_at` datetime                             NOT NULL,
    `sourceip`   varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `#__mwebinar_webinars`
(
    `id`          int(11) NOT NULL,
    `name`        varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `alias`       varchar(255)                         NOT NULL,
    `catid`       INT( 10 ) UNSIGNED NOT NULL DEFAULT '0',
    `published`   int (11) NOT NULL DEFAULT '1',
    `params`      longtext COLLATE utf8_unicode_ci     NOT NULL,
    `content_end` text                                 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `#__mwebinar_pages`
    ADD PRIMARY KEY (`id`);
ALTER TABLE `#__mwebinar_webinaranswer`
    ADD PRIMARY KEY (`id`);
ALTER TABLE `#__mwebinar_webinars`
    ADD PRIMARY KEY (`id`);

ALTER TABLE `#__mwebinar_pages` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `#__mwebinar_webinaranswer` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
ALTER TABLE `#__mwebinar_webinars` MODIFY `id` int (11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

