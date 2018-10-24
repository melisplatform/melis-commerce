-- -----------------------------------------------------
-- Table `melis_ecom_lang`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `melis_ecom_lang` ;

CREATE TABLE IF NOT EXISTS `melis_ecom_lang` (
  `elang_id` INT NOT NULL AUTO_INCREMENT COMMENT 'Lang id for all the commerce module',
  `elang_locale` VARCHAR(45) NOT NULL COMMENT 'Lang\'s locale',
  `elang_name` VARCHAR(45) NOT NULL COMMENT 'Lang\'s name',
  `elang_status` TINYINT(1) NULL DEFAULT 1,
  `elang_flag` LONGTEXT NULL,
  PRIMARY KEY (`elang_id`))
ENGINE = InnoDB
COMMENT = 'This table stores the languages available for the ecommerce translations';

-- -----------------------------------------------------
-- Data for table `melis_ecom_lang`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `melis_ecom_lang` (`elang_id`, `elang_locale`, `elang_name`, `elang_status`, `elang_flag`) VALUES (1, 'en_EN', 'English', 1, 'iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAALESURBVHja7Jc/aBNxFMc/l0STtqYtihgkYLOYitjuFuwiUgfBUOgSOqS6CNqmRRqLmyjBBDQ4FLRL/TOokEEhgyC4O7RSB0MHWxEtWLGtrW2Su/s9h8ZeUlF7rV4XHzy+995v+d77vnf3fpqIsJ3mYpvtPwENcAPeMjppJlD0APXHj9/44nZvrhh3d45tsvYuAk9GdwM0nTiRkZmZb3L9+jPbuBUDmjyA1zAUIyMviMXaSaVzDPSfJJ3O0V+JqRz9A1acSufQgC+XrlpvJRXCVua06nNXYz36m0kArwtAKUVPTzvJ5FPifR0kk0/pW4/x6jje10GhoEOhaHmx7OtzP50XQDfWOIbb2lISjz+SqakFicVGN4yx2OhWJQh7AAzDJB7vYHDwEclkF4nExnBo6DGz3Rfs959/F8aHGQDKBBSJxEOuXeuit/cemUz3hhBA6d82NfxSKlkStLZekcnJeTl2LC35/Jwt/CsS6LpJT88d7oycJRod5sH9c0Sjw9z/A4Lw8egp0MptLmI9V8br8prPB8WCJYGuK27fPkPk9E2y2T5ORzJks71EIqtxZC2uznd23kJ8y9Vj9zv7MZKGjlROQSg0JKHQZZmYmJVgMLFhDAYTW5YAIBwMJmR8/JPU1Z2XsTF7OL3nkH0PtMj7g20ChDUgHAhczC8tlTAM03ZD52ue258CjwfNX8eBty+bNSBsmmbe5XL2z6yUwu12N3sApve34jFMpKQ7swPs3IGxw2NNgTINRARRpv1tQtbFld3+q3VT3CjTsAgE34/j8/kclWBlZQVqa1cJTO89TI3XiyyvOCNBbQ3LpaK1E5pKVX/B/jkDDaWkQoKPr2hoaHBUgoWFBWhsXCXwLtBCY73fUQJzXxfXKmDqfpPPMu8oAfEDBUwN2AccAfY6vJbPAq+18p3AX0YnrQgsav8vp9tN4PsALYQJa7MTgzkAAAAASUVORK5CYII=');
INSERT INTO `melis_ecom_lang` (`elang_id`, `elang_locale`, `elang_name`, `elang_status`, `elang_flag`) VALUES (2, 'fr_FR', 'Français', 1, 'iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAIDSURBVHja7Jc7jtRAEIa/dns0BDs7CAmRkxFwATJWm3IEzsBNuAQ3INp7oE1IyAHtDPb40e5HEeBh22LW9ojWTLKVuGX34+u/qsvVSkQ4p2Wc2R4BFKCBZf88pXnA5MDl9fXHO62nxbi5+TBr5m/v3s/QXvHy86dnObDOsoyrq1fJtnbx9s1kn/b2K8A6B5bOBYyxbLc10vslNgHUEafVfv/ZD+wHKYXq2wLkTy+RzgAsc4AQAk3T0bY2iQLSmn/fRe3QGoJzAOQA1gaaxtI0iQCadvz7YgFdBOCcZ7czVFWXJryreryDzhA3AAiUZUNVmXuf/wdAmAJQCjHdUIGiaJMpIBMAIjIEsNZTluZADOy1OE4Tv6tHx4sPhK4bBmFRtBhjowQpUftIF9T1Q1v/I7930NnYBY6yDFjrkrggVNV4CHQdaD1UYLersdanOQV323GAxQK9Xg2D0DnB+0TFSZAD6Scm9ARrYwUcIkIIaQAkhIkOgvRq5wDeeURIBsAMgGDjU+AcoElWH4YwPEiHALyPY0DQOp0CIvLw4oBSCmejVOy9Q+s8nQIz5tkrkO1/x0opTmZKEfo4yfaSnXL9GCC/B1CsVk/SlNqrixmpQv4CeBDK8tfkoM1mMwtgWxTTYdKnJAW8AF4Dz09clv8Avqj+TrDqn6c0A5Tq8XJ6boDfAwCDYzbVgAnDuwAAAABJRU5ErkJggg==');
INSERT INTO `melis_ecom_lang` (`elang_id`, `elang_locale`, `elang_name`, `elang_status`, `elang_flag`) VALUES (3, 'de_DE', 'Deutsch', 0, 'iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAFbSURBVHja7JfBSsNAEIa/SdZWkLYoiOAL6MWzIL6GB1/Kl/DgA3kR754EoS1tkzQz46FpG2vrceqhc9kQJszHZv5/Z8Xd2Wdk7DkOAALkQLdZI0OBMgH9j4fHL8mD64tw+fJ8loCBZBnH93eh9au3d4BBArquClWFDUcsZSkiAGzKVER+5GyT8ea3m3nZoA9VCdBNANSKzwq8KFdJu9zBdzzzx/tfeUUJ8xqABOBaY0WBFUXM/neO8DYAteLTCT6bhtT3lGHaBlDFRhNsEgOAC5TVygeu3P1tLyYkcn2w4gNAts3tQoTQ1PwfAGYWDrCsmQBOb59wMspKQ4p3Ozm5+BqgrpU85VjYnxDm9XwNoGrkKbYXVLXVA640R3jUMIS5tVRgthoigs4ATFtN6L6g6p10wmWYFtOpMR4No50AQAW4AG6A82CCT+BVmjtBr1kjowTGcric7hvgewDckqdvKTbn2gAAAABJRU5ErkJggg==');
INSERT INTO `melis_ecom_lang` (`elang_id`, `elang_locale`, `elang_name`, `elang_status`, `elang_flag`) VALUES (4, 'pt_PT', 'Português', 0, 'iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAO6SURBVHja7JdNaFxVFMd/9703eZkkk8mn0TpRi9ai0lQXUqGLgOAnFLpsV27s1p37rLoquFHXKn6CoLhxowipFkGhoNDRVpOYLxOTZiYzmbzPe66LeZl5U8b2aSTZ9MDjzmPux++ec/73nqeMMRymWRyy3QVQgA24SXuQpoHAAQbPfj6zZansznjr3Wv/g+8V93360YgDFJVSTE+eyDw2Pz267/XDchmg6ACuFiHQMdtBA4NJYqMAWu/tmCn0+l/Ji4JuMlbNsa3/bulnFYsQRACu0wyG4EUBfhxmojdBsK/dG9/HxBEATQDReHGAF2ebWDxvf/7P5ZAwBRCLsBsHeHfwwHjOo6Tq1B+pk1+1sG/+NxUbx4E4TgEYTT3cpRH5XQcU7JAXx1b4JSji9Hq4hW2C/h708Zj8lRx2Q/07AMCEYRsgkpid0Ge3Swj6lc9rhTmuz04wtSxYQwEbFZfRJZfFhsXIuSrFd2KcWnYIYwwkedTMAS3UIw9fh21E1ew489gPDO/kObJa5tE/J1kbsdDjLtaGgLuAnhtg6/wGY28MgiRHW3qrKtXuiUQLRLd4oB56BAmAQmEwFOyIOKghIQx/KYx9c5FytZ9YDCcveGy/cA77VEC0GBL37GBvWXfaelOSUYzpyAHR7IQekcQd/Utmi826z7cLk0y9dIOlH3+nOvgAsQhfLSzTeO4mD+eHMU9VaSznKSy5GU+hCGwr5QGjaYQekeiOfhvGpxHVWNv1Of9xwB+nbdZEiLShz7c59knI+stVzEqFvq0QXe3JLEN7sNC+DWOt0WIQ0/ksh33sGJfVYo75+22Gqp9RmhjhwXtHuWfzC347qiid9cjVFP3XbBDJ9miNRB0hiDBKECMdoJ5R/FQZ5ELpMrOvPMOZ9z7gyTevEuYs5gauY706gvfErwxd6cdqmCTbMlzBRjpPwlgLBpAu5/qHlWOcLFYYOlpm5sxpppdCni9v0hMV6V2cp/K+y+jbvTQlkFmHSJQCiHQMtoUx7asnrZ7X55/m2eIKpx6/SjQV4JUq9G5aWN/lyX+fA5obaEktLb9/ANDpEGitsR1rb5rWHOn26+0jlL0hjqt1pmd3KdzowV63OlcxdP/dpQ7SOg0ggm2SE+o2thLkWeEhLl1ezbLK7S80LW0ViNatK/xgCkGFSArAiGkVIAezfhvA2at6FIqBXD5bOTcwsG+IPck7gMYY6tu1zIMr9dq+AZLs0QqYAE4A4wdclm8AP6vkm6CQtAdpAVBXdz9ODxvg7wEAoID60Wsq3U4AAAAASUVORK5CYII=');
INSERT INTO `melis_ecom_lang` (`elang_id`, `elang_locale`, `elang_name`, `elang_status`, `elang_flag`) VALUES (5, 'it_IT', 'Italiano', 0, 'iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAIISURBVHja7JdPbtNQEIe/cZwGpKSVkBAS4gq9AOoZ2LFgwU1YcRUWXXIYNogLsCgiaVr/fW9mWMQUOymOFVnJprPxwvOs7/1mfuP3xN05ZSScOJ4ABJgAs+Z5zFCgSoHz918//54k8miWN5QA1+8+Dfryzw8fB2xdeH395UUKXCQiXL257OryGMHAeHb1dm9O/f0HwEUKzNSNWgO3VYZvMRxiUrv5tVnbWFxEaNs9uTiHugKYpQDRjSLWlBpGKa6X1U4ZO1FWECIAKYCaUsaaMtajAFhZ9iecTfE2QHQlDyVFqMZRoMj736cJplsKrENBFstxFLjP9xAKVPU/gGBKVhfkcSQF8n4Aw/G6BRBNuQvFeD2Q5Vse7npZ1PBqC+C+KqgaF0irc+UQgP8p4A4iSIwQQwtAlUxLgsXdQe2HlCDrTwg1TCZdF2ShIJiOM+RXq/6E6ZTJYtFqQlVUHXPfHYEH1cD756gq1imBBUwMc9tdc9Astv7/kDketN0DhsNGgVFsYHuaxPHQdoFGfJKMBuADAGLUrgvSNMEZSYF9GxFBY2sUm21qP9oJecB3rHFcAmCqiHC0EJHNpv8CuDnCUQkeANKN0xwRYX72fJyj9nw+oAT+AKDuznp1u3fRcrkcBLBcr/e3STOSBHgFXAIvj3wsvwG+SXMnWDTPY0YF3MnT5fTUAH8GAC+EMksDZTY+AAAAAElFTkSuQmCC');
INSERT INTO `melis_ecom_lang` (`elang_id`, `elang_locale`, `elang_name`, `elang_status`, `elang_flag`) VALUES (6, 'es_ES', 'Español', 0, 'iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAALvSURBVHja7JfNa1VHGMZ/M2dOTq7x5t4YQ2IERTE0UrR2kdouumhpkYK4c+XCjUvXXfbf6KpQ/BO0IujC4iKLaCFSooG2QkQqkptocnNzz8fMvC7OubnHj6I1crLJC8Mwc+YwzzzPPO/MKBFhJ0Ozw7ELQAEBEBV1leGAxADDT7/7YZWg4vm15sCNq/sM0EAH1L79ptL504UFgIYBIrEWSRL8izVASurQb0uvS702pmTjre7Sv9Jry9Z3PdKEJAWIDADeI90YieNKVi/dGMksADmALMN3N/HdbjX8D4T4NO0DEOuQjQ7S6VTDQBCALTEwecuCboN03jSpAucUAphAwH8M8yuQBOo9CciANrBZGiRYa+hsDPHiqUYBzUnHnqEOxjgQtR0OcgCUAUgbpLQHAkjXIlo/d0E5xDmW0UxcDtH7E7Tdbg5KywAsyAZI7gIRxdwvMVNfGfZ+GbJy12COH2JkxrG+vMS9612+vjCY53GRku3etfBirLwOQHoSZDn7xjF98SzB3CyPf20xODhO89iPJLMxzXNXOHnQoPVNcOZV/78n+/nkwVsYKABkzyzxHQXzazwaEtbjjFP/LjExOUXnd4tdDdl7pkXYHPhACUJQjf5pKN4Wuvi8hIJsCuGM4/TYCn8/qXHttysk93+Cjb/Ah2CkP/5/F4dIVsoDkpEbLfeYVinNM+MM1ENsy3NJz/NkzvP4D8/n5z9FxibQWJDgg13QA6DzTGwLcXKEaTTMjYcPWXxUQyJNdvQ00eEvGD1gWLKa2/8sYKOhbTAgeF9iwHsHQQ+AItQpnx19wMjwc4aPBJhui9HvhcagYV232NN2GG15Myup/z7MXmkL3vut3k8Wo8ZiFARIRWeBqtWIveN4vDadM+AcBEHu04rCO9ffA867908mfJyzoCdBvglFUBUCUCUAhQ3zFKnr9eokKOQ2gPPA8/Z6pXfCYrc5BYwDJ4Cxiq/ly8CfqngT1Iu6ykiAttp9nO40gJcDAKapaesBVlkjAAAAAElFTkSuQmCC');
INSERT INTO `melis_ecom_lang` (`elang_id`, `elang_locale`, `elang_name`, `elang_status`, `elang_flag`) VALUES (7, 'ru_RU', 'русский (Russian)', 0, 'iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAFtSURBVHja7JfBTsMwDIY/Zx0TMJgEQkg8ADeuPBhHXowbJ96Ax0ACwQZlTWxzWEe7MSYmjfQyX6K4rvLVTpy/4u50aYGObQcgQA8Y1GNOU2BaAMfu/tzJ14ucBGDUYQVGoU5/VzbYnYIdQAFwfXPHxek+H9OUZdGDQcHre9UARDPGZaSsdDHS61Y1H1f5aT1bF9fyqzlVsgYgJWNSRj5rABFhfkuKyCLTkt/df8T8ZvNYVVsEiOqMy0Ssnf9tVQr0grQyoMakTETNA9DvBUaH/TaAom5YJnGibkTVVgmi4wZmeQBchBjbm9ASTiDT+jgsZuDh/pY+jk+rzdWEL81XHdPl1wZ7RAlczgFME/QK2HQP+Jq5rydXTU0GTG2mhzIqZKtPXJh1JoM/NpMtSSHMWgC2QTfbkhT7BihmLXKWgTAc5iuBNwBqwMv4Les1XO82FeAcuALOMkuBJ+BRalF61IE4nQJj2f2cdg3wNQCQl7beZ527hwAAAABJRU5ErkJggg==');
INSERT INTO `melis_ecom_lang` (`elang_id`, `elang_locale`, `elang_name`, `elang_status`, `elang_flag`) VALUES (8, 'zh', '中文 (Chinese)', 0, 'iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAKfSURBVHja7Je7bhNREIa/s7uOnYtjAYqQaOioQKJECtR06SgQouUxKLi8Qd6AAqVGVAhRIJ6AAhAdIkERYCex1/buzpmhWF/Wlk0Uxdk0mWZ3j+bo/DP/zH9mnZlxnhZwznYBwAEhUB08yzQPJBGwvvvgYdOFs5PhakbQMPz+gpPlAq69fnU5AhoucNTubs70izaM6m1P/D4CXdz56ddvAI0IqJr3kKbo4RHDtnTO5Y4txf8yfDMYrRd9ZrXxcO88v6CxDmkCUI0AEMV6fayf5FFfV2Q3AMk3SAeCVWNlK6P/KUJ+5GDmKYgd800/gcznZwGYF0y6GF0IYHUr4WC7CgaWDKIR6H1QtCVo350u/0sRlmVjAIigcY/lzTYr9zMsdVx5GtN9VyF+WxlFkX5ZDP8WhaiXAgDv0VaH9k5KeCmldk/of4xo7xiQ5jxGsHTTIz8D/J9TZsCAJB0D0EzQuIP1epgl7D+psP44xnoyJnDNqN1Jid9EWPd0kqEYlqZFCjK0HUOlR/OlA/M0X4Bb7kDgCNcV2QtpPh/qhw00zAp6RmGdqe/JdecVK2bARNBOB0uSYR+BGXRg6YZSf5Tx91kt14FhOw1aDbPx+7Gpz32dCEhWBOAh7owqs2jZdzjcNiyO5/fdSS1LIQwnKZA4hhkA/AFkewu+BSoVwnp9sgjxHlQLnB7H5zz+bWpt1jXkUZHJGnBqmNrUxnmaZnN0zv5/8BCyGpZNAzArZOCMzQzLCl0gIlTMsBIBiBTuAhWBKBq32JmPQQ5frAEdRl7ihKzqxzOhVz+6w8tJgBsFHQDoSdRsQRQMAUSjycU5grW1EimwEQCvBq2jo1JH4kG1eQdcBW4BGyWP5b+Bz27wT1AfPMu0BGi7i5/T8wbwbwD6HHepwTz9DwAAAABJRU5ErkJggg==');
INSERT INTO `melis_ecom_lang` (`elang_id`, `elang_locale`, `elang_name`, `elang_status`, `elang_flag`) VALUES (9, 'ja_JP', '日本語 (Japanese)', 0, 'iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAJHSURBVHja7JfPaxNBFMc/s9lkk5Kmra0kelFbJJUiiAqKpaBQ8OhB8SIIgid78OLBq4f+A0UPIoh4VLyK6EHBQpWChypUUhV/IP6qCWzTTTbdnefBVNNizVRi9tIvDMvCzJvv+773Zt4oESFKWESMDQIKiAFO/dtOhIBvAxkRKUbivVKbLKArwgh0WXX5o4ITeRLa/7RKhLBYZOn1WwiWiA/0Y/X1omKx/08g+PCR0sRlgvfvUChAIYTEevvoHhsjMTS47jLMi8hLk8nVqWnmxy9hkfjDESJofLpPnyF98rhpFQwaKxB8+szXixeIkUII1/SmeGWCeP8OnP17W6iACF9OnaVWmAXigNSXSoMZ6v8ay0my9dE9VNxujQLh/Heqz54CSaC66hRvJPOTSFguUpt5gbNvT2uS0H8yjV6soAhMy4TFu/dbR0B7HrpUQpEwJKCRctm8DEUEpdSak5Ijh5CwZly1Go/U0dEmaSW/A9msK7K35HAGhhDtg9ZNRoAV6yA1fMCcgNa6WcGSvX0Trb2mBEK9QPbGNZTz9ytmeU8zAkBiV57c1esE4iISIKJXjYBAXHrOnSd94ljzMDXsma9UKmKKyuSUvNm2UwoqIXMqLXOqUwoqIa96cuLeumNsx/M8AfK2qQK/EnL4INtnZ6g8nsR78BDxfTpGj5A6PIKVyRjbWaGA67rSbriuK0DeWq8CrcKKJIzicbJMwAZCrTWlUqmtBOpOhwrIAruBzW0W4RvwXNWb0s4ImlMfWFAbj9OoCfwYAOvZ37XX6A+0AAAAAElFTkSuQmCC');
INSERT INTO `melis_ecom_lang` (`elang_id`, `elang_locale`, `elang_name`, `elang_status`, `elang_flag`) VALUES (10, 'ko_KR', '한국어/조선말 (Korean)', 0, 'iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAATXSURBVHja7JdbTFN3HMc/p609XKogoKQXSEiYZVmJJVnEBJnJRFaXJcgTiSTuaXObe8KHZU4fiMsemDPRxCxehjFe5iJGTDalsw6YD/NCoMuGtmFeQhwUBm2Rlra05/z3AFSuDmImL35fzvnfft/v+f9/l/+RhBAsJzQsM14JkAAtIE8+XyYUIKYDVgkh/Mvy9ZKUpQEyFpqQSCR40ShJJBLPG87QTG7/HITDYU6cOMHjx4+JRqNLJo7H4/T19XH8+HEGBgYWmiYv6IRXrlxBlmUuNV2io6MDn8+3aHK/309nZyenT58mMzOTy5cvLy0Kurq6ePjwIRqNhozMDFwu15IEhEIhmpubMZlMDA8PU1hYSGdn57xzdbM7IpEI58+fZ/369Xi9XgoKCsjKysJutyfnqIEAUbebRG8vqAKdxYJcYkebkwNAfn4+drud1NRU+vv78fl8XL16FbPZTG5u7pwwtAohPFMdLpcLt9vN0NAQRUVF3Llzh4aGBgwGAwBjP19n9ML3qIEAaCY3UFXRrFxJenU1hurqpPGTJ08iSRIejweLxUJOTg47duxAkqSpKCiacwQVFRVs27YNk8nErVu3qKmpeUZ+/TrBg1+jDg0liVHVidenTxk5epTQxYtJW2VlZbS3t5OXl0dJSQm1tbVJ8gV3YAojIyO0tbVRVVU1kTXCYQZ37kQdCYJ25smlCAUtKkJRiepk1jQ2ojObAWhpacFms2GxWObLA0ULCpiNJ9daUfbWoU1Pn1qNBOjVBL/JeXRpjJjEKFv8Xazd9SGGjz5eTCIq0i3Ws3+9H2Dj2BhajQYxuXWpIsYReSPfSW+CkAAJp24V3967j2GRdhctoDOxmr/DRj6I9yCQAMEPehsHNW+wIjjKhCxoGcvll7hCzVIECCHmOMdsbLDl8n7qFrr0hbyuDPGndi3X9K+hVeKAQJ2qMPFxRm2l/0k8leI10xvTMTw8zLFjx5LtdzcXYjFnckms48uUzTSvKEpGgaoKVFUQjyukGlJ4p2pDct2pU6d48ODB8wWoqjpj8ObNm+zbt4+zZ89y7tw5AAzpehq+qETEoxANoVdjCKGiCoEqBOMJhdjTKHs/fYs8SxYAra2tHDl8mG8OHeLitPCczikB1lgs5tHr9QA0NjZy48YNfD4fdrsdp9NJe3s72dnZAFz4sZs9XzkZeBKEqWMTAsPqND7/pJzPdpUlSfbU7QEJbt++jdVqxWw2U19fjyRJjI+PI8vyRBhGIhFPSkpKMhWXl5dTWVmJ0Wikt7eXR48e0dTU9Ox4AmP81PYXv9/rR1FV1hXk8N7b68g3PavsdXV1GI1GsrOzGRwc5MyZMzidzmQ+iEQipKWlTQgIh8OetLS05OKWlhbq6+txOBx4vV68Xi/79+9n+/bti/Lsjo4Oamtr2bRpE7IsU1xcTEFBAQ6HY0a5NxgME6lYUZQZBhwOB6Wlpbjdbvr6+ti9ezc2m23R1dBoNHLgwAG6u7sJhUK4XK4Z5LP9zhoMBsVsBINBsXXrVnH37l0RCoXEUhGLxYTH4xEVFRWip6dnXvuAFcAaCATmNRKPx8WLYiEbfr9fAFYdoKiqSiAQeKkX0sk8oEhALlAMrHnJl+J/gD+kyUvpyoUup/8jYsCo9OrndLkF/DsAr6rTe48R7+MAAAAASUVORK5CYII=');
INSERT INTO `melis_ecom_lang` (`elang_id`, `elang_locale`, `elang_name`, `elang_status`, `elang_flag`) VALUES (11, 'hi_IN', 'हिंदी (Hindi)', 0, 'iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAACXBIWXMAAAsTAAALEwEAmpwYAAAAIGNIUk0AAHolAACAgwAA+f8AAIDpAAB1MAAA6mAAADqYAAAXb5JfxUYAAAJDSURBVHja7JfNaxNBGIef2WwalaahhaaYUm1ta4tivViUHqxSRISeBG/SP0vwVPDkTfAiqIh4ED8OORRrFT8qghZrpYkxu9mdmddDYhtFwak4ufQHy+zC7Mwz837MO0pE6KQCOqxdAAVkgFyr9SkDNEKgp7J4+YsEfudXKqCwsNgXAgUJFNlDM36X/+klQCEEclgLOkHiKiBt1qHtu91q8pv3X/vwx35qTw+iGwC5EABrER0hOvazfB2DNQC0ADSkcfPxoUwWbPozgCR1JI08BX8GTBuAWIM0akhS9+eFOtnyjgkRWXH9vx5r3n+oYrAMFvMUunM7CEU1Ge4E/tmrz9x7tMrxyQEA7j95x5HRImemh/5/Ko6TlBt3XnDp/CTfooRKrcHFuQnKz9f4uF7bUSp2MkF5eY2NzYgktdx9vEqlGnNuZoSxA72srdeYPzvuZALnHWikBhGIE009SqnVU+qxBiBqtc4mcClKjo73c/vhW05OlZg9McSF06PMnRrm1oM3TE+V/nqcH3M6A+T3dTE/O8aV62X29+cZKRW4dnOJsYO9DA8WnAEUMJGm6UoYugXExmbE8usNjLEcHu6jVOx2SwNak81mm2E4fnUByQQkrezkrKdu3bsyWYLmUdDMhNoYwjBA8FOgKgXa6m0Aay2Imy/8kwSs0dtOaI1BKZ/VEFjTHgVWUPgjUKjmrm+dhghKKbq79nqDsLINYESE6malE1W5UcAAcAzo9zz5OrCkWneCfKv1qQbwVe1eTjsN8H0AbQf7MRxAQMIAAAAASUVORK5CYII=');

COMMIT;