net use LPT1 /delete
net use LPT1 \\DESKTOP-24BBSC4\XP-80C
@echo OFF
FOR /R %%y IN (printable*.rcpt) DO copy %%y LPT1
PAUSE
