# ScoutOrg
ScoutOrg är ett bibliotek i php som innehåller en datastruktur för en scoutorganisation. Biblioteket är skrivet för att vara grunden till många olika applikationer där organisationen hanteras på scoutkårers hemsidor. Datastrukturen är skriven så att den fungerar bäst för data som hämtas från scoutnet. Biblioteket är dock skrivet så att det även går att implementera andra datakällor som en egen databas eller en blandning av en egen databas och scoutnet.
Det finns ett förhoppningsvis uppdaterat [klassdiagram](https://github.com/scouternasetjanster/ScoutOrg/blob/master/docs/classDiag.asta) som kan läsas med [astah community](http://astah.net/editions/community).
Just nu finns endast support för joomla men utrymme finns att skapa en plugin för t.ex. wordpress.

## Installation och användning
Just nu finns endast installation för joomla och detta görs just nu manuellt.

Biblioteket använder några paket som inte är inkluderade i php:

* cURL: ``` sudo apt install php-curl ```
* APCu: ``` sudo apt install php-apcu ```
* Semaphores

Cache-funktionen stöds inte av windows implicit. Antingen kan den inaktiveras genom att sätta livstiden till noll eller så kan man installera eller implementera följande funktioner:

* sem_get
* sem_acquire
* sem_release
* apcu_fetch
* apcu_store

Har du hemsidan på linux ska det inte vara något problem om den har php 7.
Har den php 5 kan du behöva installera APCu och Semaphores.

### Joomla

#### Installation
Se först till att ha scoutnets webbkoppling aktiverad och ha api-nycklarna.
1. Hämta rätt release från github eller bygg den själv. Den ska heta 'scoutorg.zip'.
2. Logga in som admin i joomla och gå till Extensions->Manage.
3. Välj Upload Package File och dra in zipfilen.
4. Konfigurera komponenten 'ScoutOrg Component' i System->'Global Configuration' att använda rätt kår och api-nycklar. (Måste göras innan den används av andra extensions)
    * Konfiguera alternativt access till komponentens adminsida.

#### Advancerad konfiguration
Eftersom scoutnet inte ger ut grenarna som varje avdelning är i så ges istället lösningen i komponenten som konfiguerades i steg 4. Klickar man på Components->ScoutOrg så får man upp listor över grenar och avdelningar där man kan skapa grenar och sätta avdelningarnas grentillhörighet.

Om man inte vill att användarna ska behöva vänta i 20 sekunder ibland när cachen laddar in så kan man sätta upp ett cronjob efter instruktionerna i [/src/cronjob/](https://github.com/scouternasetjanster/ScoutOrg/tree/master/src/cronjob)

#### Användning (Utveckling utav extensions)
När biblioteket ska användas av en annan extension behöver man två rader kod:
```
jimport('scoutorg.loader');
$scoutOrg = ScoutOrgLoader::load();
```
Då kommer $scoutOrg att sättas till en singleton-instans av typen [Org\Lib\ScoutOrg](https://github.com/scouternasetjanster/ScoutOrg/blob/master/src/Org/Lib/ScoutOrg.php) som lever tills http-förfrågan är över. Använder flera olika extensions sig av den laddaren kommer de alltså få ut samma instans för att förhindra redundant dataprocessering. I den senaste [dokumentationen](https://github.com/scouternasetjanster/ScoutOrg/releases/download/v1.0/doc_exdev.zip) kan man hitta samtliga metoder för att hämta de olika objekten i datastrukturerna.

## Att bygga projektet och generera dokumentation
Om man vill bygga installationsfiler eller generera dokumentationen för biblioteket krävs att kunna köra 'make' samt några program beroende på vad man bygger.

### Bygga projektet
För att bygga paketet för joomla kan man köra ``` make ``` eller ``` make joomla ```.
Zipfilen kommer då att hamna i 'build/joomla/'.
Om projektet skulle expandera och börjar ge stöd för att bygga en plugin för t.ex. wordpress så kommer ``` make ``` att bygga för samtliga mål.

``` make joomla ``` kräver programmet zip: ``` sudo apt install zip ```

### Generera dokumentation
För att generera dokumentationen kör man ``` make doc ```. Detta kräver att man har php och phpdoc (som /bin/phpdoc).

1. php 7.0 +
2. [phpdoc](https://github.com/phpDocumentor/phpDocumentor2)