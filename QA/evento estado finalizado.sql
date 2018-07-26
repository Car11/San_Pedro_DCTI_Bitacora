USE controlaccesocdc_dbp;
SET GLOBAL event_scheduler = ON;
create EVENT setEstadoFinalizado
	ON SCHEDULE EVERY 1 DAY STARTS CURRENT_TIMESTAMP 
    ON COMPLETION PRESERVE
	DO
    UPDATE controlaccesocdc_dbp.formulario
	SET IDESTADO=3 
	WHERE FECHASALIDA< NOW();
        
    SHOW PROCESSLIST;
	show events from controlaccesocdc_dbp;
     
    
    