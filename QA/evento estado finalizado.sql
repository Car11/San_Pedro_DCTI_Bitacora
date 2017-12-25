USE controlaccesocdc_dbp;
SET GLOBAL event_scheduler = ON;
create EVENT setEstadoFinalizado
	on SCHEDULE EVERY 1 DAY STARTS CURRENT_TIMESTAMP 
	DO
    UPDATE controlaccesocdc_dbp.formulario
	SET IDESTADO=3 
	WHERE FECHASALIDA< NOW();
        
    SHOW PROCESSLIST;
     
    
    