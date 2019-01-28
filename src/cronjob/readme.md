# ScoutnetCache.php
ScoutnetCache.php är en fil som kan användas i ett cron job eller en liknande lösning för att på schemalagd tid ladda in data in i cachen så att man slipper vänta i 20 sekunder då och då när cachen är utdaterad.

## Installation
Eftersom APCu är en in-memory cache som är starkt kopplad till den process den används i t.ex. apache2, så måste den hanteras genom den processen. Därför måste scriptet köras genom att initiera ett http-request till ScoutnetCache.php med t.ex. ``` wget ```. För att vidare göra att en användare inte kan göra det så kan filen läggas i en mapp tillsammans med en fil [.htaccess](.htaccess) (så länge AllowOverride är satt till Allow i rootinställningarna eller en övre mapp):

```
<If "!(-R '127.0.0.1')">
	Require all denied
</If>
```

Denna kod gör att alla requests som inte kommer från localhost stoppas för filer som ligger i eller under samma mapp som .htaccess.

I ScoutnetCache.php behöver man ställa in ett antal parametrar vilka visas tydligt.

* $orgLib: Måste sättas till samma filer som används av hemsidan då alla cache-entries sparas med namn utifrån bland annat pathen för Org/Scoutnet/ScoutnetConnection.php. Använder man biblioteket fast på en annan plats så kommer hemsidans installation av scoutorg inte använda samma cache-entries.
* $domain: Behöver bara ändras om man ska använda scoutnets testserver eller en egen emulator.
* $cacheLifeTime: Sätts till det antal sekunder som cachen ska leva. Bör sättas till ett tal som är större än den frekvens som cronjobbet körs i. Annars kommer det finnas en lucka där användaren ändå behöver vänta i 20 sekunder för att ladda sidan.
* $memberListKey: Apinyckeln för att hämta medlemslistorna.
* $customListsKey: Apinyckeln för att hämta customlistorna.

### Noteringar
Denna fil skulle också kunna användas när en person vill tvinga sina uppgifter att uppdateras lokalt på hemsidan. Eftersom semaforen i ScoutnetConnection.php inte används så kommer två samtidiga hämtningar skriva över varandra (inget problem tekniskt sett).