UPDATE `demands` SET 
 status = 'TENDER_OPEN_TO_BIDS' WHERE status = 'TENDER_OPEN_TO_BID';

UPDATE `demands` SET 
 status = 'TENDER_CLOSED_TO_BIDS' WHERE status = 'TENDER_CLOSED_TO_BID';

UPDATE `demands` SET 
 status = 'TENDER_READY_TO BIDS' WHERE status = 'TENDER_READY_FOR_BID';


